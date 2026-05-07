<?php

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

test('admin can onboard employee with a linked password protected account', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $department = Department::create([
        'name' => 'Operations',
        'description' => 'Operations team',
        'status' => 'active',
        'manager_id' => null,
    ]);

    $password = 'SecurePass123!';
    $email = 'new.employee.'.uniqid().'@example.com';

    $this->actingAs($admin)
        ->post(route('employees.store'), [
            'first_name' => 'New',
            'last_name' => 'Employee',
            'email' => $email,
            'phone' => '09170000000',
            'address' => 'Test Address',
            'department_id' => $department->id,
            'position' => 'Staff',
            'hire_date' => Carbon::today()->toDateString(),
            'employment_status' => 'active',
            'password' => $password,
            'password_confirmation' => $password,
        ])
        ->assertRedirect(route('employees.index', absolute: false));

    $user = User::where('email', $email)->first();
    $employee = Employee::where('email', $email)->first();

    $this->assertNotNull($user);
    $this->assertNotNull($employee);
    $this->assertSame($user->id, $employee->user_id);
    $this->assertTrue(Hash::check($password, $user->password));
});

test('employee can time in and time out from their own account', function () {
    $department = Department::create([
        'name' => 'Support',
        'description' => 'Support team',
        'status' => 'active',
        'manager_id' => null,
    ]);

    $user = User::factory()->create([
        'role' => 'employee',
        'name' => 'Clock In User',
    ]);

    $employee = Employee::create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'first_name' => 'Clock',
        'last_name' => 'In',
        'email' => $user->email,
        'phone' => '09001112222',
        'address' => 'Test Address',
        'position' => 'Staff',
        'hire_date' => Carbon::today()->subMonth()->toDateString(),
        'employment_status' => 'active',
    ]);

    $this->actingAs($user)
        ->post(route('attendance.time-in'))
        ->assertRedirect();

    $attendance = Attendance::where('employee_id', $employee->id)
        ->whereDate('attendance_date', Carbon::today())
        ->first();

    $this->assertNotNull($attendance);
    $this->assertNotNull($attendance->time_in);
    $this->assertSame('present', $attendance->status);

    $this->actingAs($user)
        ->post(route('attendance.time-out'))
        ->assertRedirect();

    $attendance->refresh();

    $this->assertNotNull($attendance->time_out);
});

test('admin can export attendance report as csv and filter by department', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $department = Department::create([
        'name' => 'Finance',
        'description' => 'Finance team',
        'status' => 'active',
        'manager_id' => null,
    ]);

    $user = User::factory()->create(['role' => 'employee']);
    $employee = Employee::create([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'first_name' => 'Report',
        'last_name' => 'User',
        'email' => 'report.user.'.uniqid().'@example.com',
        'phone' => '09002223333',
        'address' => 'Test Address',
        'position' => 'Analyst',
        'hire_date' => Carbon::today()->subDays(10)->toDateString(),
        'employment_status' => 'active',
    ]);

    Attendance::create([
        'employee_id' => $employee->id,
        'attendance_date' => Carbon::today()->toDateString(),
        'time_in' => Carbon::now()->subHours(2),
        'time_out' => Carbon::now()->subMinutes(15),
        'status' => 'present',
    ]);

    $this->actingAs($admin)
        ->get(route('reports.attendance', [
            'department' => $department->id,
            'start_date' => Carbon::today()->subDay()->toDateString(),
            'end_date' => Carbon::today()->toDateString(),
        ]))
        ->assertOk()
        ->assertSee('Report User');

    $this->actingAs($admin)
        ->get(route('reports.export', [
            'type' => 'attendance',
            'format' => 'csv',
            'department' => $department->id,
            'start_date' => Carbon::today()->subDay()->toDateString(),
            'end_date' => Carbon::today()->toDateString(),
        ]))
        ->assertOk()
        ->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
});
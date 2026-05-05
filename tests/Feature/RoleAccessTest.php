<?php

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;

function createEmployeeUser(array $attributes = []): array
{
    $department = Department::create([
        'name' => $attributes['department_name'] ?? 'Operations',
        'description' => 'Test department',
        'status' => 'active',
        'manager_id' => $attributes['manager_id'] ?? null,
    ]);

    $user = User::factory()->create(array_merge([
        'role' => 'employee',
    ], $attributes['user'] ?? []));

    $employee = Employee::create(array_merge([
        'user_id' => $user->id,
        'department_id' => $department->id,
        'first_name' => 'Test',
        'last_name' => 'Employee',
        'email' => 'employee.'.uniqid().'@example.com',
        'phone' => '09123456789',
        'address' => 'Test Address',
        'position' => 'Staff',
        'hire_date' => Carbon::today()->subMonth()->toDateString(),
        'employment_status' => 'active',
    ], $attributes['employee'] ?? []));

    return [$user, $employee, $department];
}

test('admin can access employee and report modules', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    $this->actingAs($admin)
        ->get(route('employees.index'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('departments.index'))
        ->assertOk();

    $this->actingAs($admin)
        ->get(route('reports.index'))
        ->assertOk();
});

test('hr can manage employees and departments', function () {
    $hr = User::factory()->create(['role' => 'hr']);

    $this->actingAs($hr)
        ->get(route('employees.index'))
        ->assertOk();

    $this->actingAs($hr)
        ->get(route('departments.index'))
        ->assertOk();
});

test('manager can view leave requests for managed departments', function () {
    $manager = User::factory()->create(['role' => 'manager']);

    $department = Department::create([
        'name' => 'Engineering',
        'description' => 'Engineering team',
        'manager_id' => $manager->id,
        'status' => 'active',
    ]);

    $employeeUser = User::factory()->create(['role' => 'employee']);
    $employee = Employee::create([
        'user_id' => $employeeUser->id,
        'department_id' => $department->id,
        'first_name' => 'Managed',
        'last_name' => 'Worker',
        'email' => 'managed.worker@example.com',
        'phone' => '09000000001',
        'address' => 'Worker Address',
        'position' => 'Developer',
        'hire_date' => Carbon::today()->subMonths(2)->toDateString(),
        'employment_status' => 'active',
    ]);

    LeaveRequest::create([
        'employee_id' => $employee->id,
        'leave_type' => 'vacation',
        'start_date' => Carbon::tomorrow()->toDateString(),
        'end_date' => Carbon::tomorrow()->addDay()->toDateString(),
        'reason' => 'Test leave',
        'status' => LeaveRequest::STATUS_PENDING,
        'approved_by' => null,
    ]);

    $this->actingAs($manager)
        ->get(route('leave-requests.index'))
        ->assertOk()
        ->assertSee('Managed Worker');
});

test('employee can submit and cancel leave requests', function () {
    [$user, $employee] = createEmployeeUser();

    $this->actingAs($user)
        ->post(route('leave-requests.store'), [
            'leave_type' => 'sick_leave',
            'start_date' => Carbon::tomorrow()->toDateString(),
            'end_date' => Carbon::tomorrow()->addDay()->toDateString(),
            'reason' => 'Medical appointment',
        ])
        ->assertRedirect(route('leave-requests.index', absolute: false));

    $leaveRequest = LeaveRequest::first();

    $this->assertNotNull($leaveRequest);
    $this->assertSame($employee->id, $leaveRequest->employee_id);
    $this->assertSame(LeaveRequest::STATUS_PENDING, $leaveRequest->status);

    $this->actingAs($user)
        ->patch(route('leave-requests.cancel', $leaveRequest))
        ->assertRedirect();

    $this->assertSame(LeaveRequest::STATUS_CANCELLED, $leaveRequest->fresh()->status);
});

test('admin can approve leave requests', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    [$employeeUser, $employee] = createEmployeeUser();

    $leaveRequest = LeaveRequest::create([
        'employee_id' => $employee->id,
        'leave_type' => 'vacation',
        'start_date' => Carbon::tomorrow()->toDateString(),
        'end_date' => Carbon::tomorrow()->addDay()->toDateString(),
        'reason' => 'Family time',
        'status' => LeaveRequest::STATUS_PENDING,
        'approved_by' => null,
    ]);

    $this->actingAs($admin)
        ->patch(route('leave-requests.update-status', $leaveRequest), [
            'status' => 'approved',
        ])
        ->assertRedirect();

    $leaveRequest->refresh();

    $this->assertSame(LeaveRequest::STATUS_APPROVED, $leaveRequest->status);
    $this->assertSame($admin->id, $leaveRequest->approved_by);
});

test('employee cannot access admin employee module', function () {
    [$user] = createEmployeeUser();

    $this->actingAs($user)
        ->get(route('employees.index'))
        ->assertRedirect(route('dashboard', absolute: false));
});

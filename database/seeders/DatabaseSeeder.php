<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@hris.test'],
            [
                'name' => 'System Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'email_verified_at' => now(),
            ]
        );

        $hr = User::updateOrCreate(
            ['email' => 'hr@hris.test'],
            [
                'name' => 'HR Staff',
                'password' => Hash::make('password'),
                'role' => 'hr',
                'email_verified_at' => now(),
            ]
        );

        $manager = User::updateOrCreate(
            ['email' => 'manager@hris.test'],
            [
                'name' => 'Department Manager',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'email_verified_at' => now(),
            ]
        );

        $employeeUser = User::updateOrCreate(
            ['email' => 'employee@hris.test'],
            [
                'name' => 'Regular Employee',
                'password' => Hash::make('password'),
                'role' => 'employee',
                'email_verified_at' => now(),
            ]
        );

        $itDepartment = Department::updateOrCreate(
            ['name' => 'Information Technology'],
            [
                'description' => 'Manages internal systems and application support.',
                'manager_id' => $manager->id,
                'status' => 'active',
            ]
        );

        $hrDepartment = Department::updateOrCreate(
            ['name' => 'Human Resources'],
            [
                'description' => 'Handles recruitment, staffing, and personnel support.',
                'manager_id' => $hr->id,
                'status' => 'active',
            ]
        );

        $employeeProfile = Employee::updateOrCreate(
            ['email' => 'employee.profile@hris.test'],
            [
                'user_id' => $employeeUser->id,
                'department_id' => $itDepartment->id,
                'first_name' => 'Regular',
                'last_name' => 'Employee',
                'phone' => '09123456789',
                'address' => '123 Employee Street',
                'position' => 'Software Engineer',
                'hire_date' => Carbon::now()->subMonths(8)->toDateString(),
                'employment_status' => 'active',
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'manager.profile@hris.test'],
            [
                'user_id' => $manager->id,
                'department_id' => $itDepartment->id,
                'first_name' => 'Department',
                'last_name' => 'Manager',
                'phone' => '09998887777',
                'address' => '456 Manager Avenue',
                'position' => 'IT Manager',
                'hire_date' => Carbon::now()->subYears(2)->toDateString(),
                'employment_status' => 'active',
            ]
        );

        Employee::updateOrCreate(
            ['email' => 'hr.profile@hris.test'],
            [
                'user_id' => $hr->id,
                'department_id' => $hrDepartment->id,
                'first_name' => 'HR',
                'last_name' => 'Staff',
                'phone' => '09990001111',
                'address' => '789 HR Lane',
                'position' => 'HR Officer',
                'hire_date' => Carbon::now()->subYear()->toDateString(),
                'employment_status' => 'active',
            ]
        );

        Attendance::updateOrCreate(
            [
                'employee_id' => $employeeProfile->id,
                'attendance_date' => Carbon::today()->toDateString(),
            ],
            [
                'time_in' => Carbon::today()->setTime(8, 5),
                'time_out' => null,
                'status' => 'present',
                'remarks' => 'On time',
            ]
        );

        LeaveRequest::updateOrCreate(
            [
                'employee_id' => $employeeProfile->id,
                'start_date' => Carbon::today()->addDays(2)->toDateString(),
                'end_date' => Carbon::today()->addDays(3)->toDateString(),
            ],
            [
                'leave_type' => 'vacation',
                'reason' => 'Family vacation',
                'status' => LeaveRequest::STATUS_PENDING,
                'approved_by' => null,
            ]
        );

        $this->call([
            DepartmentSeeder::class,
            EmployeeSeeder::class,
            AttendanceSeeder::class,
            LeaveRequestSeeder::class,
        ]);
    }
}

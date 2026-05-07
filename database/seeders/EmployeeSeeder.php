<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $employeeCount = 200;
        $departments = Department::all();

        if ($departments->isEmpty()) {
            return;
        }

        $users = User::factory()
            ->count($employeeCount)
            ->state(['role' => 'employee'])
            ->create();

        $users->each(function (User $user) use ($departments): void {
            Employee::factory()
                ->for($user)
                ->state([
                    'email' => $user->email,
                    'department_id' => $departments->random()->id,
                ])
                ->create();
        });
    }
}

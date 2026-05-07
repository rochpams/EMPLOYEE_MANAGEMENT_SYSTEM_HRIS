<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departmentCount = 20;
        $managerCount = 8;

        $managerUsers = User::factory()
            ->count($managerCount)
            ->state(['role' => 'manager'])
            ->create();

        $departments = Department::factory()->count($departmentCount)->create();

        $departments->each(function (Department $department) use ($managerUsers): void {
            $department->update(['manager_id' => $managerUsers->random()->id]);
        });
    }
}

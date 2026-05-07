<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\LeaveRequest;
use App\Models\User;
use Illuminate\Database\Seeder;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();
        $approvers = User::whereIn('role', ['admin', 'hr', 'manager'])->get();

        if ($employees->isEmpty()) {
            return;
        }

        $total = max(1, (int) round($employees->count() * 0.6));

        for ($i = 0; $i < $total; $i++) {
            $employee = $employees->random();
            $status = fake()->randomElement([
                LeaveRequest::STATUS_PENDING,
                LeaveRequest::STATUS_APPROVED,
                LeaveRequest::STATUS_REJECTED,
                LeaveRequest::STATUS_CANCELLED,
            ]);

            $approvedBy = null;
            if (in_array($status, [LeaveRequest::STATUS_APPROVED, LeaveRequest::STATUS_REJECTED], true)) {
                $approvedBy = $approvers->isEmpty() ? null : $approvers->random()->id;
            }

            LeaveRequest::factory()
                ->for($employee)
                ->state([
                    'status' => $status,
                    'approved_by' => $approvedBy,
                ])
                ->create();
        }
    }
}

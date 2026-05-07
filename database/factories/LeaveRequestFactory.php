<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\LeaveRequest;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<LeaveRequest>
 */
class LeaveRequestFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('+1 days', '+30 days');
        $endDate = Carbon::instance($startDate)->addDays(fake()->numberBetween(1, 4));

        return [
            'employee_id' => Employee::factory(),
            'leave_type' => fake()->randomElement(['vacation', 'sick', 'emergency', 'unpaid']),
            'start_date' => Carbon::instance($startDate)->format('Y-m-d'),
            'end_date' => $endDate->format('Y-m-d'),
            'reason' => fake()->sentence(12),
            'status' => LeaveRequest::STATUS_PENDING,
            'approved_by' => null,
        ];
    }
}

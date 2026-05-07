<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Attendance>
 */
class AttendanceFactory extends Factory
{
    public function definition(): array
    {
        $status = fake()->randomElement(['present', 'late', 'absent']);
        $attendanceDate = fake()->dateTimeBetween('-60 days', 'now');
        $timeIn = null;
        $timeOut = null;
        $remarks = null;

        if ($status !== 'absent') {
            $base = Carbon::instance($attendanceDate)->setTime(8, fake()->numberBetween(0, 40));
            $timeIn = $status === 'late' ? $base->copy()->addMinutes(fake()->numberBetween(10, 45)) : $base;
            $timeOut = $timeIn->copy()->addHours(fake()->numberBetween(8, 9))->addMinutes(fake()->numberBetween(0, 45));
            $remarks = $status === 'late' ? 'Late arrival' : 'On time';
        } else {
            $remarks = 'Absent';
        }

        return [
            'employee_id' => Employee::factory(),
            'attendance_date' => Carbon::instance($attendanceDate)->format('Y-m-d'),
            'time_in' => $timeIn,
            'time_out' => $timeOut,
            'status' => $status,
            'remarks' => $remarks,
        ];
    }
}

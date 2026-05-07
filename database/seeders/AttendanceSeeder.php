<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        if ($employees->isEmpty()) {
            return;
        }

        $employees->each(function (Employee $employee): void {
            $entries = fake()->numberBetween(6, 14);
            $dates = collect(range(1, $entries))
                ->map(fn () => fake()->dateTimeBetween('-60 days', 'now')->format('Y-m-d'))
                ->unique()
                ->values();

            foreach ($dates as $date) {
                Attendance::factory()
                    ->for($employee)
                    ->state(['attendance_date' => Carbon::parse($date)->format('Y-m-d')])
                    ->create();
            }
        });
    }
}

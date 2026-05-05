<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // If sessions are configured to use the database but the DB is
        // unreachable or the `sessions` table doesn't exist, fall back to
        // file sessions to avoid crashing the request pipeline.
        try {
            DB::connection()->getPdo();

            if (config('session.driver') === 'database') {
                $table = config('session.table', 'sessions');
                if (! Schema::hasTable($table)) {
                    config(['session.driver' => 'file']);
                    Log::warning("Session table '{$table}' not found; falling back to file sessions.");
                }
            }
        } catch (\Throwable $e) {
            if (config('session.driver') === 'database') {
                config(['session.driver' => 'file']);
                Log::warning('Database unavailable for sessions; falling back to file sessions. '.$e->getMessage());
            }
        }
    }
}

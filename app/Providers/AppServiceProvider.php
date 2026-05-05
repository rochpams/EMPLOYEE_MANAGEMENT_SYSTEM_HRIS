<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        if (app()->environment('production')) {
            URL::forceScheme('https');
        }

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
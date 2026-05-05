<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            try {
                $table->dropForeign(['approved_by']);
            } catch (Throwable $e) {
                // Ignore if the foreign key does not exist in this environment.
            }
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreign('approved_by')
                ->references('id')
                ->on('users')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            try {
                $table->dropForeign(['approved_by']);
            } catch (Throwable $e) {
                // Ignore if the foreign key does not exist in this environment.
            }
        });

        Schema::table('leave_requests', function (Blueprint $table) {
            $table->foreign('approved_by')
                ->references('id')
                ->on('employees')
                ->nullOnDelete();
        });
    }
};

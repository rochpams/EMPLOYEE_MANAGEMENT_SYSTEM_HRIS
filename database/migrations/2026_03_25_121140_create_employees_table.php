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
        Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
    $table->foreignId('department_id')->constrained()->cascadeOnDelete();
    $table->string('first_name');
    $table->string('last_name');
    $table->string('email')->unique();
    $table->string('phone')->nullable();
    $table->text('address')->nullable();
    $table->string('position');
    $table->date('hire_date');
    $table->string('employment_status')->default('active');
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};

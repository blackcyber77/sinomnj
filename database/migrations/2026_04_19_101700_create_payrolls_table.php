<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('attendance_days')->default(0);
            $table->decimal('daily_rate', 12, 2)->default(0);
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->unsignedInteger('kpi_score')->default(0);
            $table->decimal('bonus_amount', 12, 2)->default(0);
            $table->decimal('total_salary', 12, 2)->default(0);
            $table->timestamps();

            $table->unique(['payroll_period_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};

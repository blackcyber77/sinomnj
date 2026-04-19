<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('shift_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->string('shift_name');
            $table->decimal('opening_cash', 12, 2)->default(0);
            $table->decimal('cash_sales', 12, 2)->default(0);
            $table->decimal('qris_sales', 12, 2)->default(0);
            $table->decimal('merchant_sales', 12, 2)->default(0);
            $table->decimal('closing_cash_actual', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['report_date', 'shift_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shift_reports');
    }
};

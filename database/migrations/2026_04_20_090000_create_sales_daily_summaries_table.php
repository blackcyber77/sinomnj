<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_daily_summaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->decimal('walk_in_revenue', 12, 2)->default(0);
            $table->decimal('shopee_revenue', 12, 2)->default(0);
            $table->decimal('grab_revenue', 12, 2)->default(0);
            $table->decimal('gojek_revenue', 12, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'report_date']);
            $table->index('report_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_daily_summaries');
    }
};

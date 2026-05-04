<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales_product_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_daily_summary_id')->constrained()->cascadeOnDelete();
            $table->string('channel');
            $table->string('product_name');
            $table->unsignedInteger('quantity')->default(0);
            $table->decimal('revenue', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['channel', 'product_name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales_product_items');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales_product_items', function (Blueprint $table) {
            $table->foreignId('menu_variant_id')->nullable()->after('sales_daily_summary_id')->constrained('menu_variants')->nullOnDelete();
            $table->index(['sales_daily_summary_id', 'menu_variant_id']);
        });
    }

    public function down(): void
    {
        Schema::table('sales_product_items', function (Blueprint $table) {
            $table->dropConstrainedForeignId('menu_variant_id');
            $table->dropIndex(['sales_daily_summary_id', 'menu_variant_id']);
        });
    }
};

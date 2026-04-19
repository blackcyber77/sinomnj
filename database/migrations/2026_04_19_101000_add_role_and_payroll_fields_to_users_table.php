<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('staff')->after('email');
            $table->decimal('daily_rate', 12, 2)->default(0)->after('role');
            $table->boolean('is_active')->default(true)->after('daily_rate');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'daily_rate', 'is_active']);
        });
    }
};

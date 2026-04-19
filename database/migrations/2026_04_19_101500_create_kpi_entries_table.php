<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kpi_entries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kpi_variable_id')->constrained()->cascadeOnDelete();
            $table->date('entry_date');
            $table->unsignedInteger('score')->default(0);
            $table->text('notes')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->timestamps();

            $table->index(['entry_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kpi_entries');
    }
};

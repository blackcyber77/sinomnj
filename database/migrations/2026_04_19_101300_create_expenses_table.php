<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('shift_report_id')->nullable()->constrained()->nullOnDelete();
            $table->date('expense_date');
            $table->string('category');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('receipt_path')->nullable();
            $table->string('status')->default('pending');
            $table->foreignId('validated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('validated_at')->nullable();
            $table->text('validation_note')->nullable();
            $table->timestamps();

            $table->index(['expense_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};

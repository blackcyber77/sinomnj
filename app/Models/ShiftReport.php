<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ShiftReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
        'shift_name',
        'opening_cash',
        'cash_sales',
        'qris_sales',
        'merchant_sales',
        'closing_cash_actual',
        'notes',
    ];

    protected $appends = [
        'total_revenue',
        'cash_expected',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'opening_cash' => 'decimal:2',
            'cash_sales' => 'decimal:2',
            'qris_sales' => 'decimal:2',
            'merchant_sales' => 'decimal:2',
            'closing_cash_actual' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    public function getTotalRevenueAttribute(): float
    {
        return (float) $this->cash_sales + (float) $this->qris_sales + (float) $this->merchant_sales;
    }

    public function getCashExpectedAttribute(): float
    {
        return (float) $this->opening_cash + (float) $this->cash_sales - (float) $this->expenses()->sum('amount');
    }
}

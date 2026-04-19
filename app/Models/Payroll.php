<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payroll extends Model
{
    use HasFactory;

    protected $fillable = [
        'payroll_period_id',
        'user_id',
        'attendance_days',
        'daily_rate',
        'base_salary',
        'kpi_score',
        'bonus_amount',
        'total_salary',
    ];

    protected function casts(): array
    {
        return [
            'daily_rate' => 'decimal:2',
            'base_salary' => 'decimal:2',
            'bonus_amount' => 'decimal:2',
            'total_salary' => 'decimal:2',
        ];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(PayrollPeriod::class, 'payroll_period_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

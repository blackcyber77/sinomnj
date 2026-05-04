<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesDailySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'report_date',
        'walk_in_revenue',
        'shopee_revenue',
        'grab_revenue',
        'gojek_revenue',
        'notes',
    ];

    protected $appends = [
        'merchant_revenue_total',
        'total_revenue',
    ];

    protected function casts(): array
    {
        return [
            'report_date' => 'date',
            'walk_in_revenue' => 'decimal:2',
            'shopee_revenue' => 'decimal:2',
            'grab_revenue' => 'decimal:2',
            'gojek_revenue' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesProductItem::class);
    }

    public function getMerchantRevenueTotalAttribute(): float
    {
        return (float) $this->shopee_revenue + (float) $this->grab_revenue + (float) $this->gojek_revenue;
    }

    public function getTotalRevenueAttribute(): float
    {
        return (float) $this->walk_in_revenue + $this->merchant_revenue_total;
    }
}

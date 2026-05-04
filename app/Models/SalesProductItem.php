<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesProductItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sales_daily_summary_id',
        'menu_variant_id',
        'channel',
        'product_name',
        'quantity',
        'revenue',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'integer',
            'revenue' => 'decimal:2',
        ];
    }

    public function summary(): BelongsTo
    {
        return $this->belongsTo(SalesDailySummary::class, 'sales_daily_summary_id');
    }

    public function menuVariant(): BelongsTo
    {
        return $this->belongsTo(MenuVariant::class);
    }
}

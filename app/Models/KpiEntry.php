<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kpi_variable_id',
        'entry_date',
        'score',
        'notes',
        'status',
        'validated_by',
        'validated_at',
    ];

    protected function casts(): array
    {
        return [
            'entry_date' => 'date',
            'validated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function variable(): BelongsTo
    {
        return $this->belongsTo(KpiVariable::class, 'kpi_variable_id');
    }

    public function validator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}

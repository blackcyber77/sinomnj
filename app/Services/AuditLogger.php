<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AuditLogger
{
    public static function log(string $action, string $entityType, ?int $entityId, ?array $oldValues = null, ?array $newValues = null): void
    {
        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }

    public static function forModel(string $action, Model $model, ?array $oldValues = null): void
    {
        self::log(
            action: $action,
            entityType: class_basename($model),
            entityId: (int) $model->getKey(),
            oldValues: $oldValues,
            newValues: $model->getAttributes(),
        );
    }
}

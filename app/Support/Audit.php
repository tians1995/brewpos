<?php

namespace App\Support;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Models\User;

class Audit
{
    public static function record(
        string $action,
        string $entity,
        string $description,
        ?User $user = null,
        ?Request $request = null,
        ?int $entityId = null,
        array $metadata = []
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_role' => $user?->role,
            'action' => $action,
            'entity' => $entity,
            'entity_id' => $entityId,
            'description' => $description,
            'metadata' => $metadata ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}

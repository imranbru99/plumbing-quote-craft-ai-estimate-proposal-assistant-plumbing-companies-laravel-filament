<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'action', 'subject_type', 'subject_id', 'user_id', 'payload', 'ip_address', 'created_at',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function record(
        string $action,
        ?string $subjectType = null,
        ?int $subjectId = null,
        ?int $userId = null,
        array $payload = []
    ): self {
        return static::query()->create([
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'user_id' => $userId,
            'payload' => $payload,
            'ip_address' => request()?->ip(),
            'created_at' => now(),
        ]);
    }
}
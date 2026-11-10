<?php

namespace App\Models;

use App\Services\SlugService;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quote extends Model
{
    protected $fillable = [
        'service_request_id', 'customer_id', 'created_by', 'approved_by', 'quote_number',
        'slug', 'title', 'status', 'ai_summary', 'ai_confidence', 'subtotal', 'tax', 'total',
        'valid_until', 'approved_at', 'sent_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'ai_confidence' => 'decimal:2',
            'subtotal' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'valid_until' => 'date',
            'approved_at' => 'datetime',
            'sent_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Quote $quote) {
            if (empty($quote->slug)) {
                $quote->slug = app(SlugService::class)->make($quote->title ?: $quote->quote_number ?: 'quote');
            }
        });
    }

    public function serviceRequest(): BelongsTo
    {
        return $this->belongsTo(ServiceRequest::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class)->orderBy('sort_order');
    }

    public function approve(User $user): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => $user->id,
            'approved_at' => now(),
        ]);

        AuditLog::record('quote.approved', self::class, $this->id, $user->id, [
            'quote_number' => $this->quote_number,
            'total' => $this->total,
        ]);
    }
}
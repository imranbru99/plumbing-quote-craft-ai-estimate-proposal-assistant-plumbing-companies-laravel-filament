<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsentRecord extends Model
{
    protected $fillable = [
        'customer_id', 'channel', 'consented', 'source', 'captured_at', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'consented' => 'boolean',
            'captured_at' => 'datetime',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
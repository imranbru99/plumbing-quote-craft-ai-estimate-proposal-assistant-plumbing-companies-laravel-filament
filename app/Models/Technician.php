<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Technician extends Model
{
    protected $fillable = [
        'user_id', 'employee_code', 'license_number', 'license_expires_on',
        'specialty', 'is_available', 'safety_certified', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'license_expires_on' => 'date',
            'is_available' => 'boolean',
            'safety_certified' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jobTickets(): HasMany
    {
        return $this->hasMany(JobTicket::class);
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ServiceRequest extends Model
{
    protected $fillable = [
        'customer_id', 'assigned_technician_id', 'intake_by', 'request_number', 'title',
        'description', 'job_type', 'priority', 'status', 'site_address', 'preferred_date',
        'estimated_hours', 'outlet_count', 'panel_amps', 'source', 'raw_intake',
    ];

    protected function casts(): array
    {
        return [
            'preferred_date' => 'date',
            'estimated_hours' => 'decimal:1',
            'raw_intake' => 'array',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function technician(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'assigned_technician_id');
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function jobTickets(): HasMany
    {
        return $this->hasMany(JobTicket::class);
    }
}
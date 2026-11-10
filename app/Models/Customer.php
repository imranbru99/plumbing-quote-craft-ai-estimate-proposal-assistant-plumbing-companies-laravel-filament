<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'company', 'address', 'city', 'state', 'zip',
        'customer_type', 'messaging_consent', 'notes',
    ];

    protected function casts(): array
    {
        return ['messaging_consent' => 'boolean'];
    }

    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }
}
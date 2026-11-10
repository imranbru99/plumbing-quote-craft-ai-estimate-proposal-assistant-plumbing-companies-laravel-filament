<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntegrationSetting extends Model
{
    protected $fillable = ['provider', 'label', 'is_enabled', 'config', 'last_synced_at', 'notes'];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'config' => 'array',
            'last_synced_at' => 'datetime',
        ];
    }
}
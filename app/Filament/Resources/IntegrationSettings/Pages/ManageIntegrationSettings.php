<?php

namespace App\Filament\Resources\IntegrationSettings\Pages;

use App\Filament\Resources\IntegrationSettings\IntegrationSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManageIntegrationSettings extends ManageRecords
{
    protected static string $resource = IntegrationSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
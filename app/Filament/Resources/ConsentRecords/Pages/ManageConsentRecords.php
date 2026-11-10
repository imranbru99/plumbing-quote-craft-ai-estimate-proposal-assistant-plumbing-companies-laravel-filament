<?php

namespace App\Filament\Resources\ConsentRecords\Pages;

use App\Filament\Resources\ConsentRecords\ConsentRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageConsentRecords extends ManageRecords
{
    protected static string $resource = ConsentRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
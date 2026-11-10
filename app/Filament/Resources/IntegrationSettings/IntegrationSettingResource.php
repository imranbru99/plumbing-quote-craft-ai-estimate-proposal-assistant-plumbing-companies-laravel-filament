<?php

namespace App\Filament\Resources\IntegrationSettings;

use App\Filament\Resources\IntegrationSettings\Pages\ManageIntegrationSettings;
use App\Models\IntegrationSetting;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class IntegrationSettingResource extends Resource
{
    protected static ?string $model = IntegrationSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('integrations_hub');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('provider')->disabled(),
            TextInput::make('label')->disabled(),
            Toggle::make('is_enabled'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('label'),
                TextColumn::make('provider')->badge(),
                IconColumn::make('is_enabled')->boolean(),
                TextColumn::make('last_synced_at')->dateTime(),
            ])
            ->recordActions([EditAction::make()])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageIntegrationSettings::route('/')];
    }
}
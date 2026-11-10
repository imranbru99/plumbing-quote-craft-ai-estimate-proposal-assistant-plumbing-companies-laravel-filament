<?php

namespace App\Filament\Resources\ModuleSettings;

use App\Filament\Resources\ModuleSettings\Pages\ManageModuleSettings;
use App\Models\ModuleSetting;
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

class ModuleSettingResource extends Resource
{
    protected static ?string $model = ModuleSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static string|\UnitEnum|null $navigationGroup = 'Settings';

    protected static ?string $navigationLabel = 'Feature Modules';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->disabled(),
            TextInput::make('name')->disabled(),
            Textarea::make('description')->disabled()->columnSpanFull(),
            Toggle::make('enabled')->helperText('Core modules can be toggled for niche fit; disable only if unused.'),
            Toggle::make('is_core')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('key')->badge(),
                IconColumn::make('enabled')->boolean(),
                IconColumn::make('is_core')->boolean()->label('Core'),
                TextColumn::make('description')->limit(50),
            ])
            ->recordActions([
                EditAction::make()->after(fn () => Module::clearCache()),
            ])
            ->toolbarActions([]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageModuleSettings::route('/')];
    }
}
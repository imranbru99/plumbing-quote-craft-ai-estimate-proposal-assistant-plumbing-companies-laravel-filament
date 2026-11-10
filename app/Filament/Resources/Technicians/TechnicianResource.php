<?php

namespace App\Filament\Resources\Technicians;

use App\Filament\Resources\Technicians\Pages\ManageTechnicians;
use App\Models\Technician;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TechnicianResource extends Resource
{
    protected static ?string $model = Technician::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    protected static string|\UnitEnum|null $navigationGroup = 'Field Ops';

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('field_technicians') || Module::enabled('licensing_safety');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('user_id')->relationship('user', 'name')->searchable(),
            TextInput::make('employee_code'),
            TextInput::make('license_number'),
            DatePicker::make('license_expires_on'),
            TextInput::make('specialty'),
            Toggle::make('is_available')->default(true),
            Toggle::make('safety_certified')->default(false),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Name')->searchable(),
                TextColumn::make('employee_code'),
                TextColumn::make('license_number'),
                TextColumn::make('license_expires_on')->date(),
                TextColumn::make('specialty'),
                IconColumn::make('is_available')->boolean(),
                IconColumn::make('safety_certified')->boolean(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([DeleteBulkAction::make()]),
            ]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageTechnicians::route('/')];
    }
}
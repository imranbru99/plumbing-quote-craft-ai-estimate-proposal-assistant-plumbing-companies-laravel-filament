<?php

namespace App\Filament\Resources\CatalogItems;

use App\Filament\Resources\CatalogItems\Pages\ManageCatalogItems;
use App\Models\CatalogItem;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
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

class CatalogItemResource extends Resource
{
    protected static ?string $model = CatalogItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|\UnitEnum|null $navigationGroup = 'Catalog';

    protected static ?string $navigationLabel = 'Price Catalog';

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('price_catalog');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('sku'),
            TextInput::make('name')->required(),
            TextInput::make('category'),
            TextInput::make('unit')->default('ea'),
            TextInput::make('unit_price')->numeric()->required(),
            TextInput::make('keywords')->helperText('Comma-separated match keywords for AI drafting'),
            Toggle::make('is_active')->default(true),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')->searchable(),
                TextColumn::make('name')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('unit_price')->money('USD'),
                IconColumn::make('is_active')->boolean(),
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
        return ['index' => ManageCatalogItems::route('/')];
    }
}
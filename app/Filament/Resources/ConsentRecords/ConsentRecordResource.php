<?php

namespace App\Filament\Resources\ConsentRecords;

use App\Filament\Resources\ConsentRecords\Pages\ManageConsentRecords;
use App\Models\ConsentRecord;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ConsentRecordResource extends Resource
{
    protected static ?string $model = ConsentRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChatBubbleLeftRight;

    protected static string|\UnitEnum|null $navigationGroup = 'Compliance';

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('messaging_consent');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('customer_id')->relationship('customer', 'name')->required(),
            Select::make('channel')->options([
                'sms' => 'SMS',
                'email' => 'Email',
                'voice' => 'Voice',
            ])->required(),
            Toggle::make('consented'),
            Select::make('source')->options([
                'web_form' => 'Web form',
                'verbal' => 'Verbal',
                'paper' => 'Paper',
                'import' => 'Import',
            ]),
            DateTimePicker::make('captured_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('customer.name'),
                TextColumn::make('channel')->badge(),
                IconColumn::make('consented')->boolean(),
                TextColumn::make('source'),
                TextColumn::make('captured_at')->dateTime(),
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
        return ['index' => ManageConsentRecords::route('/')];
    }
}
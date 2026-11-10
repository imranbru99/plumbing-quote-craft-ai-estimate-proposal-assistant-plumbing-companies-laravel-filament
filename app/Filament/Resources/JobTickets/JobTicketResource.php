<?php

namespace App\Filament\Resources\JobTickets;

use App\Filament\Resources\JobTickets\Pages\ManageJobTickets;
use App\Models\JobTicket;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class JobTicketResource extends Resource
{
    protected static ?string $model = JobTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|\UnitEnum|null $navigationGroup = 'Field Ops';

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('job_tickets');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('service_request_id')->relationship('serviceRequest', 'request_number')->required(),
            Select::make('quote_id')->relationship('quote', 'quote_number'),
            Select::make('technician_id')->relationship('technician.user', 'name'),
            TextInput::make('ticket_number')->required(),
            TextInput::make('title')->required(),
            Select::make('status')->options([
                'open' => 'Open',
                'scheduled' => 'Scheduled',
                'in_progress' => 'In Progress',
                'completed' => 'Completed',
                'cancelled' => 'Cancelled',
            ])->default('open'),
            DateTimePicker::make('scheduled_for'),
            DateTimePicker::make('completed_at'),
            Textarea::make('notes')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket_number')->searchable(),
                TextColumn::make('title')->limit(40),
                TextColumn::make('technician.user.name')->label('Technician'),
                TextColumn::make('status')->badge(),
                TextColumn::make('scheduled_for')->dateTime(),
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
        return ['index' => ManageJobTickets::route('/')];
    }
}
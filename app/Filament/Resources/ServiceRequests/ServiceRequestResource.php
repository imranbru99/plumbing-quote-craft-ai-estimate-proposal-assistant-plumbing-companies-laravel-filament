<?php

namespace App\Filament\Resources\ServiceRequests;

use App\Filament\Resources\ServiceRequests\Pages\ManageServiceRequests;
use App\Models\ServiceRequest;
use App\Services\AiQuoteDrafter;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class ServiceRequestResource extends Resource
{
    protected static ?string $model = ServiceRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static string|\UnitEnum|null $navigationGroup = 'Revenue Ops';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('quote_intake') || Module::enabled('service_requests');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('customer_id')->relationship('customer', 'name')->searchable()->required(),
            Select::make('assigned_technician_id')->relationship('technician.user', 'name')->label('Technician')->searchable(),
            TextInput::make('request_number')->default(fn () => 'SR-' . now()->format('ymd') . '-' . strtoupper(Str::random(3)))->required(),
            TextInput::make('title')->required()->maxLength(255),
            Textarea::make('description')->columnSpanFull(),
            Select::make('job_type')->options([
                'water_heater' => 'Water Heater',
                'drain_cleaning' => 'Drain Cleaning',
                'leak_repair' => 'Leak Repair',
                'fixture_install' => 'Fixture Install',
                'sewer_line' => 'Sewer Line',
                'repiping' => 'Repiping',
                'commercial_fitout' => 'Commercial Fit-out',
            ]),
            Select::make('priority')->options([
                'low' => 'Low',
                'normal' => 'Normal',
                'high' => 'High',
                'emergency' => 'Emergency',
            ])->default('normal'),
            Select::make('status')->options([
                'intake' => 'Intake',
                'reviewing' => 'Reviewing',
                'quoted' => 'Quoted',
                'scheduled' => 'Scheduled',
                'closed' => 'Closed',
            ])->default('intake'),
            TextInput::make('site_address'),
            DatePicker::make('preferred_date'),
            TextInput::make('estimated_hours')->numeric(),
            TextInput::make('outlet_count')->label('Fixture count')->numeric(),
            TextInput::make('panel_amps')->label('Pipe size (in ×10)')->numeric()->helperText('Optional: e.g. 7.5" = 75'),
            Select::make('source')->options([
                'phone' => 'Phone',
                'web' => 'Web',
                'email' => 'Email',
                'walk_in' => 'Walk-in',
                'partner' => 'Partner',
            ])->default('phone'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('request_number')->searchable()->sortable(),
                TextColumn::make('title')->limit(40)->searchable(),
                TextColumn::make('customer.name')->searchable(),
                TextColumn::make('job_type')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('preferred_date')->date(),
            ])
            ->recordActions([
                Action::make('aiDraft')
                    ->label('AI Draft Quote')
                    ->icon('heroicon-o-sparkles')
                    ->visible(fn () => Module::enabled('ai_quote_drafting'))
                    ->requiresConfirmation()
                    ->action(function (ServiceRequest $record) {
                        $quote = app(AiQuoteDrafter::class)->draftFromRequest($record, auth()->id());
                        Notification::make()
                            ->title('AI quote drafted')
                            ->body("Created {$quote->quote_number} totaling \$" . number_format((float) $quote->total, 2))
                            ->success()
                            ->send();
                    }),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageServiceRequests::route('/'),
        ];
    }
}
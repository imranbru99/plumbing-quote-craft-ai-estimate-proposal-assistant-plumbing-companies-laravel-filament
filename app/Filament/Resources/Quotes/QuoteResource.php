<?php

namespace App\Filament\Resources\Quotes;

use App\Filament\Resources\Quotes\Pages\ManageQuotes;
use App\Models\JobTicket;
use App\Models\Quote;
use App\Support\Module;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
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

class QuoteResource extends Resource
{
    protected static ?string $model = Quote::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static string|\UnitEnum|null $navigationGroup = 'Revenue Ops';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return Module::enabled('ai_quote_drafting') || Module::enabled('proposal_approval');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('service_request_id')->relationship('serviceRequest', 'request_number')->required(),
            Select::make('customer_id')->relationship('customer', 'name')->required(),
            TextInput::make('quote_number')->required(),
            TextInput::make('title')->required(),
            Select::make('status')->options([
                'draft' => 'Draft',
                'pending_approval' => 'Pending Approval',
                'approved' => 'Approved',
                'sent' => 'Sent',
                'accepted' => 'Accepted',
                'rejected' => 'Rejected',
            ])->default('draft'),
            Textarea::make('ai_summary')->columnSpanFull()->rows(4),
            TextInput::make('ai_confidence')->numeric(),
            TextInput::make('subtotal')->numeric(),
            TextInput::make('tax')->numeric(),
            TextInput::make('total')->numeric(),
            DatePicker::make('valid_until'),
            Textarea::make('notes')->columnSpanFull(),
            Repeater::make('items')
                ->relationship()
                ->schema([
                    TextInput::make('description')->required()->columnSpan(2),
                    TextInput::make('quantity')->numeric()->default(1),
                    TextInput::make('unit')->default('ea'),
                    TextInput::make('unit_price')->numeric(),
                    TextInput::make('line_total')->numeric(),
                ])
                ->columns(5)
                ->columnSpanFull()
                ->defaultItems(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('quote_number')->searchable()->sortable(),
                TextColumn::make('title')->limit(36)->searchable(),
                TextColumn::make('customer.name'),
                TextColumn::make('status')->badge(),
                TextColumn::make('ai_confidence')->formatStateUsing(fn ($state) => $state !== null ? number_format(((float) $state) * 100, 0) . '%' : '—')->toggleable(),
                TextColumn::make('total')->money('USD')->sortable(),
                TextColumn::make('valid_until')->date(),
            ])
            ->recordActions([
                Action::make('submitApproval')
                    ->label('Submit Approval')
                    ->icon('heroicon-o-paper-airplane')
                    ->visible(fn (Quote $r) => Module::enabled('proposal_approval') && $r->status === 'draft')
                    ->action(function (Quote $record) {
                        $record->update(['status' => 'pending_approval']);
                        \App\Models\AuditLog::record('quote.submitted_approval', Quote::class, $record->id, auth()->id());
                        Notification::make()->title('Submitted for approval')->success()->send();
                    }),
                Action::make('approve')
                    ->label('Approve')
                    ->color('success')
                    ->icon('heroicon-o-check-badge')
                    ->visible(fn (Quote $r) => Module::enabled('proposal_approval') && in_array($r->status, ['draft', 'pending_approval'], true))
                    ->requiresConfirmation()
                    ->action(function (Quote $record) {
                        $record->approve(auth()->user());
                        Notification::make()->title('Quote approved')->success()->send();
                    }),
                Action::make('createTicket')
                    ->label('Create Job Ticket')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->visible(fn (Quote $r) => Module::enabled('job_tickets') && $r->status === 'approved')
                    ->action(function (Quote $record) {
                        JobTicket::query()->create([
                            'service_request_id' => $record->service_request_id,
                            'quote_id' => $record->id,
                            'ticket_number' => 'JT-' . now()->format('ymd') . '-' . strtoupper(Str::random(3)),
                            'title' => 'Job for ' . $record->quote_number,
                            'status' => 'open',
                            'scheduled_for' => now()->addDays(3),
                            'notes' => 'Created from approved quote ' . $record->quote_number,
                        ]);
                        Notification::make()->title('Job ticket created')->success()->send();
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
            'index' => ManageQuotes::route('/'),
        ];
    }
}
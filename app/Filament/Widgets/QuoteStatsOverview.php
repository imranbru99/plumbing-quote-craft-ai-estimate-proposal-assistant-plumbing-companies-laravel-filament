<?php

namespace App\Filament\Widgets;

use App\Models\AuditLog;
use App\Models\JobTicket;
use App\Models\Quote;
use App\Models\ServiceRequest;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class QuoteStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Open Intakes', ServiceRequest::query()->whereIn('status', ['intake', 'reviewing'])->count())
                ->description('Awaiting quote draft')
                ->icon('heroicon-o-clipboard-document-list'),
            Stat::make('Draft Quotes', Quote::query()->where('status', 'draft')->count())
                ->description('AI drafts pending review')
                ->icon('heroicon-o-sparkles'),
            Stat::make('Pending Approval', Quote::query()->where('status', 'pending_approval')->count())
                ->description('Human approval queue')
                ->icon('heroicon-o-check-badge'),
            Stat::make('Open Job Tickets', JobTicket::query()->whereIn('status', ['open', 'scheduled', 'in_progress'])->count())
                ->description('Field work from approved quotes')
                ->icon('heroicon-o-wrench-screwdriver'),
            Stat::make('Audit Events', AuditLog::query()->count())
                ->description('Compliance trail entries')
                ->icon('heroicon-o-shield-check'),
            Stat::make('Pipeline Value', '$' . number_format((float) Quote::query()->whereIn('status', ['draft', 'pending_approval', 'approved', 'sent'])->sum('total'), 0))
                ->description('Active quote totals')
                ->icon('heroicon-o-banknotes'),
        ];
    }
}
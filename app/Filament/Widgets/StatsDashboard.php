<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsDashboard extends BaseWidget
{
    protected function getStats(): array
    {
        $isAdmin = auth()->user()->hasRole('admin');

        return [
            Stat::make(
                'Total Projects',
                Project::query()
                    ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                    ->count()
            )
                ->description('Projects in the system')
                ->descriptionIcon('heroicon-o-rectangle-stack', IconPosition::Before)
                ->color('info'),
            Stat::make(
                'Total Tickets',
                Ticket::query()
                    ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                    ->count()
            )
                ->description('Tickets all projects')
                ->descriptionIcon('heroicon-o-ticket', IconPosition::Before)
                ->color('success'),
            Stat::make(
                'Queue Tickets',
                Ticket::query()
                    ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                    ->whereHas('status', fn($q) => $q->where('name', 'To Do'))
                    ->count()
            )
                ->description('Queued tickets waiting for action')
                ->descriptionIcon('heroicon-o-queue-list', IconPosition::Before)
                ->color('info'),
            Stat::make(
                'Unassignee Tickets',
                Ticket::query()
                    ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                    ->whereNull('assignee_id')
                    ->count()
            )
                ->description('Tickets without an assignee')
                ->descriptionIcon('heroicon-o-user-minus', IconPosition::Before)
                ->color('danger'),
            Stat::make(
                'Overdue Tickets',
                Ticket::query()
                    ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                    ->where('due_date', '<', now())
                    ->count()
            )
                ->description('Tickets past their due date')
                ->descriptionIcon('heroicon-o-exclamation-triangle', IconPosition::Before)
                ->color('danger'),
            $isAdmin
                ? Stat::make('Team Members', User::count())
                ->description('Registered users')
                ->descriptionIcon('heroicon-o-users', IconPosition::Before)
                ->color('gray')
                : null,
        ];
    }
}

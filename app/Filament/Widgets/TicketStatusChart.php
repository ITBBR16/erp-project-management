<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Auth;

class TicketStatusChart extends ChartWidget
{
    protected static ?string $heading = 'Ticket Status';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $isAdmin = auth()->user()->hasRole('admin');

        $labels = [];
        $counts = [];
        $colors = [];

        $statuses = TicketStatus::whereRaw('LOWER(name) != ?', ['done'])->get();

        foreach ($statuses as $status) {
            $labels[] = $status->name;

            $counts[] = Ticket::query()
                ->when(!$isAdmin, fn($q) => $q->where('user_id', Auth::id()))
                ->where('ticket_status_id', $status->id)
                ->count();

            $colors[] = $status->color ?? '#a3a3a3';
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Tiket',
                    'data' => $counts,
                    'backgroundColor' => $colors,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getOptions(): ?array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                    'position' => 'bottom',
                ],
            ],
            'scales' => [
                'x' => [
                    'display' => false,
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'display' => false,
                    ],
                ],
                'y' => [
                    'display' => false,
                    'grid' => [
                        'display' => false,
                        'drawBorder' => false,
                    ],
                    'ticks' => [
                        'display' => false,
                    ],
                ],
            ],
        ];
    }
}

<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Models\Ticket;
use App\Models\TicketStatus;
use Filament\Resources\Pages\Page;
use App\Filament\Resources\TicketResource;

class KanbanBoard extends Page
{
    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.kanban-board';

    public $statuses;
    public $tickets;

    public function mount(): void
    {
        $this->statuses = TicketStatus::all();
        $this->tickets = Ticket::with('status', 'assignee')->get();
    }
}

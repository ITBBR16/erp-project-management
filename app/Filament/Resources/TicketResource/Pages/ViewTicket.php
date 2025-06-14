<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Resources\TicketResource;
use Filament\Resources\Pages\Page;

class ViewTicket extends Page
{
    protected static string $resource = TicketResource::class;

    protected static string $view = 'filament.resources.ticket-resource.pages.view-ticket';

    public $record;

    public function mount($record): void
    {
        $this->record = TicketResource::getModel()::findOrFail($record);
    }
}

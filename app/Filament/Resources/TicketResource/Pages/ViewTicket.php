<?php

namespace App\Filament\Resources\TicketResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\TicketResource;

class ViewTicket extends ViewRecord
{
    public string $commentContent = '';

    public function addComment()
    {
        $this->validate([
            'commentContent' => 'required|string|max:1000',
        ]);

        $this->record->comments()->create([
            'user_id' => auth()->id,
            'content' => $this->commentContent,
        ]);

        $this->commentContent = '';

        $this->notify('success', 'Comment added successfully.');
    }
}

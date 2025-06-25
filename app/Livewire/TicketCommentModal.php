<?php

namespace App\Livewire;

use App\Models\Ticket;
use App\Models\Comment;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class TicketCommentModal extends Component
{
    public $ticket;
    public $commentContent = '';
    public $showModal = false;

    protected $listeners = ['showCommentModal'];

    public function showCommentModal($data)
    {
        $ticketId = is_array($data) ? $data['id'] : $data;
        $this->ticket = Ticket::with('comments.user')->findOrFail($ticketId);
        $this->showModal = true;
    }

    public function submitComment()
    {
        $this->validate([
            'commentContent' => 'required|string|max:1000',
        ]);

        Comment::create([
            'ticket_id' => $this->ticket->id,
            'user_id' => Auth::id(),
            'content' => $this->commentContent,
        ]);

        $this->commentContent = '';
        $this->ticket->refresh();

        $this->dispatchBrowserEvent('notify', ['type' => 'success', 'message' => 'Comment added.']);
    }

    public function render()
    {
        return view('livewire.ticket-comment-modal');
    }
}

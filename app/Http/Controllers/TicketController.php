<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function updateStatus(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'ticket_status_id' => 'required|exists:ticket_statuses,id',
        ]);

        Ticket::findOrFail($request->ticket_id)->update([
            'ticket_status_id' => $request->ticket_status_id,
        ]);

        return response()->json(['success' => true]);
    }
}

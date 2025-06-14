<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'ticket_id' => 'required|exists:tickets,id',
            'content' => 'required|string|max:1000',
        ]);

        Comment::create([
            'ticket_id' => $request->ticket_id,
            'user_id' => Auth::id(),
            'content' => $request->content,
        ]);

        return redirect()->back()->with('success', 'Komentar berhasil ditambahkan.');
    }
}

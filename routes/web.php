<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
    ->name('tickets.comments.store')
    ->middleware(['auth']);

Route::post('/ticket/update-status', [TicketController::class, 'updateStatus'])->name('ticket.update-status');

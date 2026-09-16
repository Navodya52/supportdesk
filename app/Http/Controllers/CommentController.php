<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Models\Ticket;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Store a newly created comment for the given ticket.
     */
    public function store(StoreCommentRequest $request, Ticket $ticket): RedirectResponse
    {
        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => $request->user()->id,
            'comment' => $request->validated('comment'),
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Comment added successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\AssignTicketRequest;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use App\Models\Category;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets with search, filtering, and pagination.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        $query = Ticket::with(['user', 'assignedAgent', 'category'])
            ->forUser($user)
            ->search($request->input('search'))
            ->filterStatus($request->input('status'))
            ->filterPriority($request->input('priority'))
            ->filterCategory($request->input('category_id'));

        // Allow agents to filter only assigned to themselves
        if ($user->isAgent() && $request->filled('assigned') && $request->input('assigned') === 'me') {
            $query->where('assigned_to', $user->id);
        }

        // Allow agents/admins to filter unassigned tickets
        if (($user->isAdmin() || $user->isAgent()) && $request->filled('assigned') && $request->input('assigned') === 'unassigned') {
            $query->whereNull('assigned_to');
        }

        $tickets = $query->latest()->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('tickets.index', compact('tickets', 'categories'));
    }

    /**
     * Show the form for creating a new ticket.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('tickets.create', compact('categories'));
    }

    /**
     * Store a newly created ticket in storage.
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        $ticket = Ticket::create([
            'user_id' => $request->user()->id,
            'category_id' => $request->input('category_id'),
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'priority' => $request->input('priority'),
            'status' => Ticket::STATUS_OPEN,
        ]);

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->ticket_number} has been created successfully.");
    }

    /**
     * Display the specified ticket with its details and comments.
     */
    public function show(Request $request, Ticket $ticket): View
    {
        Gate::authorize('view', $ticket);

        $ticket->load([
            'user',
            'assignedAgent',
            'category',
            'comments.user',
        ]);

        $agents = $request->user()->isAdmin()
            ? User::where('role', User::ROLE_AGENT)->where('is_active', true)->orderBy('name')->get()
            : collect();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    /**
     * Show the form for editing the ticket.
     */
    public function edit(Ticket $ticket): View
    {
        Gate::authorize('update', $ticket);

        $categories = Category::orderBy('name')->get();
        $agents = User::where('role', User::ROLE_AGENT)->where('is_active', true)->orderBy('name')->get();

        return view('tickets.edit', compact('ticket', 'categories', 'agents'));
    }

    /**
     * Update the specified ticket in storage.
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('update', $ticket);

        $data = $request->validated();
        $user = $request->user();

        // If employee, they can only close or reopen
        if ($user->isEmployee()) {
            $newStatus = $data['status'];
            $ticket->status = $newStatus;

            if ($newStatus === Ticket::STATUS_CLOSED) {
                $ticket->closed_at = now();
            } elseif ($newStatus === Ticket::STATUS_OPEN) {
                $ticket->closed_at = null;
                $ticket->resolved_at = null;
            }

            $ticket->save();

            return redirect()->route('tickets.show', $ticket)
                ->with('success', "Ticket {$ticket->ticket_number} updated successfully.");
        }

        // For admin and agent
        $ticket->status = $data['status'];
        $ticket->priority = $data['priority'];

        if (isset($data['category_id'])) {
            $ticket->category_id = $data['category_id'];
        }
        if (isset($data['title'])) {
            $ticket->title = $data['title'];
        }
        if (isset($data['description'])) {
            $ticket->description = $data['description'];
        }
        if (array_key_exists('resolution', $data)) {
            $ticket->resolution = $data['resolution'];
        }

        if ($data['status'] === Ticket::STATUS_RESOLVED) {
            $ticket->resolved_at = $ticket->resolved_at ?? now();
        } elseif ($data['status'] === Ticket::STATUS_CLOSED) {
            $ticket->closed_at = $ticket->closed_at ?? now();
            $ticket->resolved_at = $ticket->resolved_at ?? now();
        } else {
            // Reopened or in progress
            if ($ticket->status === Ticket::STATUS_OPEN || $ticket->status === Ticket::STATUS_IN_PROGRESS) {
                $ticket->closed_at = null;
            }
        }

        $ticket->save();

        return redirect()->route('tickets.show', $ticket)
            ->with('success', "Ticket {$ticket->ticket_number} updated successfully.");
    }

    /**
     * Assign or reassign ticket to an agent.
     */
    public function assign(AssignTicketRequest $request, Ticket $ticket): RedirectResponse
    {
        Gate::authorize('assign', $ticket);

        $assignedTo = $request->validated('assigned_to');
        $ticket->assigned_to = $assignedTo;

        if ($assignedTo && $ticket->status === Ticket::STATUS_OPEN) {
            $ticket->status = Ticket::STATUS_IN_PROGRESS;
        }

        $ticket->save();

        $message = $assignedTo
            ? "Ticket {$ticket->ticket_number} has been assigned successfully."
            : "Assignment removed for Ticket {$ticket->ticket_number}.";

        return redirect()->route('tickets.show', $ticket)->with('success', $message);
    }

    /**
     * Remove the specified ticket from storage.
     */
    public function destroy(Ticket $ticket): RedirectResponse
    {
        Gate::authorize('delete', $ticket);

        $ticketNumber = $ticket->ticket_number;
        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', "Ticket {$ticketNumber} has been deleted.");
    }
}

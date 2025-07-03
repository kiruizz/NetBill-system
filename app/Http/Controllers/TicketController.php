<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Ticket;
use App\Models\User;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TicketController extends Controller
{
    /**
     * Display a listing of tickets with filtering and pagination
     */
    public function index(Request $request)
    {
        $query = Ticket::with(['client', 'assignedTo', 'createdBy']);

        // Apply filters
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('subject', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('ticket_number', 'like', '%' . $request->search . '%');
            });
        }

        $tickets = $query->orderBy('created_at', 'desc')->paginate(20);
        $clients = Client::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('tickets.index', compact('tickets', 'clients', 'users'));
    }

    /**
     * Show the form for creating a new ticket
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        return view('tickets.create', compact('clients', 'users'));
    }

    /**
     * Store a newly created ticket
     */
    public function store(StoreTicketRequest $request)
    {
        $validated = $request->validated();

        DB::transaction(function () use (&$validated) {
            $validated['status'] = 'open';
            $validated['created_by'] = Auth::id();
            $validated['ticket_number'] = $this->generateTicketNumber();
            Ticket::create($validated);
        });

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket created successfully.');
    }

    /**
     * Display the specified ticket
     */
    public function show(Ticket $ticket)
    {
        $ticket->load(['client', 'assignedTo', 'createdBy']);
        return view('tickets.show', compact('ticket'));
    }

    /**
     * Show the form for editing the specified ticket
     */
    public function edit(Ticket $ticket)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $users = User::orderBy('name')->get();
        
        return view('tickets.edit', compact('ticket', 'clients', 'users'));
    }

    /**
     * Update the specified ticket
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();
        $ticket->update($validated);
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket updated successfully.');
    }

    /**
     * Remove the specified ticket
     */
    public function destroy(Ticket $ticket)
    {
        $ticket->delete();
        
        return redirect()->route('tickets.index')
            ->with('success', 'Ticket deleted successfully.');
    }

    /**
     * Update ticket status
     */
    public function updateStatus(UpdateTicketRequest $request, Ticket $ticket)
    {
        $validated = $request->validated();
        $ticket->update([
            'status' => $validated['status'],
            'resolution_notes' => $validated['notes'] ?? null,
            'resolved_at' => $validated['status'] === 'resolved' ? now() : null,
        ]);
        return redirect()->back()
            ->with('success', 'Ticket status updated successfully.');
    }

    /**
     * Generate unique ticket number
     */
    protected function generateTicketNumber(): string
    {
        $year = date('Y');
        $count = Ticket::whereYear('created_at', $year)->count() + 1;
        
        return 'TKT-' . $year . '-' . str_pad($count, 6, '0', STR_PAD_LEFT);
    }
}
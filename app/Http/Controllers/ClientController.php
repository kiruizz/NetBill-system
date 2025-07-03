<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\ServicePlan;
use App\Models\Subscription;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-clients');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::with(['activeSubscription.servicePlan'])
                        ->orderBy('name')
                        ->paginate(20);

        return view('clients.index', compact('clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $servicePlans = ServicePlan::where('status', 'active')->get();
        return view('clients.create', compact('servicePlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'client_type' => 'required|in:individual,business,corporate',
            'connection_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $client = Client::create($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Client created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        $client->load(['subscriptions.servicePlan', 'devices', 'invoices', 'payments', 'tickets']);
        return view('clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        $servicePlans = ServicePlan::where('status', 'active')->get();
        return view('clients.edit', compact('client', 'servicePlans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $client->id,
            'phone' => 'nullable|string|max:20',
            'national_id' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive,suspended',
            'client_type' => 'required|in:individual,business,corporate',
            'connection_date' => 'nullable|date',
            'notes' => 'nullable|string',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')
                        ->with('success', 'Client updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('clients.index')
                        ->with('success', 'Client deleted successfully.');
    }

    /**
     * Display client subscriptions
     */
    public function subscriptions(Client $client)
    {
        $subscriptions = $client->subscriptions()->with('servicePlan')->paginate(10);
        return view('clients.subscriptions', compact('client', 'subscriptions'));
    }

    /**
     * Display client invoices
     */
    public function invoices(Client $client)
    {
        $invoices = $client->invoices()->orderBy('invoice_date', 'desc')->paginate(10);
        return view('clients.invoices', compact('client', 'invoices'));
    }

    /**
     * Display client payments
     */
    public function payments(Client $client)
    {
        $payments = $client->payments()->orderBy('payment_date', 'desc')->paginate(10);
        return view('clients.payments', compact('client', 'payments'));
    }
}

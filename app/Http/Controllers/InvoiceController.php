<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Client;
use App\Models\ServicePlan;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:manage-invoices');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Invoice::with(['client', 'servicePlan']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by client
        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        // Filter by date range
        if ($request->filled('from_date')) {
            $query->whereDate('invoice_date', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('invoice_date', '<=', $request->to_date);
        }

        $invoices = $query->orderBy('invoice_date', 'desc')->paginate(20);
        $clients = Client::orderBy('name')->get();

        return view('invoices.index', compact('invoices', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $servicePlans = ServicePlan::where('status', 'active')->orderBy('name')->get();
        
        return view('invoices.create', compact('clients', 'servicePlans'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_plan_id' => 'nullable|exists:service_plans,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Calculate amounts
        $validated['subtotal'] = $validated['amount'];
        $validated['total_amount'] = $validated['amount'] + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        $validated['balance_due'] = $validated['total_amount'];
        $validated['status'] = 'draft';

        // Generate invoice number
        $validated['invoice_number'] = 'INV-' . date('Y') . '-' . str_pad(Invoice::whereYear('created_at', date('Y'))->count() + 1, 6, '0', STR_PAD_LEFT);

        // Remove amount field as it's not in the database
        unset($validated['amount']);

        Invoice::create($validated);

        return redirect()->route('invoices.index')
                        ->with('success', 'Invoice created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        $invoice->load(['client', 'servicePlan', 'payments']);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                            ->with('error', 'Cannot edit paid invoices.');
        }

        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $servicePlans = ServicePlan::where('status', 'active')->orderBy('name')->get();
        
        return view('invoices.edit', compact('invoice', 'clients', 'servicePlans'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.show', $invoice)
                            ->with('error', 'Cannot update paid invoices.');
        }

        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_plan_id' => 'nullable|exists:service_plans,id',
            'amount' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        // Calculate amounts
        $validated['subtotal'] = $validated['amount'];
        $validated['total_amount'] = $validated['amount'] + ($validated['tax_amount'] ?? 0) - ($validated['discount_amount'] ?? 0);
        $validated['balance_due'] = $validated['total_amount'] - $invoice->payments()->sum('amount');

        // Remove amount field as it's not in the database
        unset($validated['amount']);

        $invoice->update($validated);

        return redirect()->route('invoices.index')
                        ->with('success', 'Invoice updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {
        if ($invoice->status === 'paid') {
            return redirect()->route('invoices.index')
                            ->with('error', 'Cannot delete paid invoices.');
        }

        if ($invoice->payments()->exists()) {
            return redirect()->route('invoices.index')
                            ->with('error', 'Cannot delete invoices with payments.');
        }

        $invoice->delete();

        return redirect()->route('invoices.index')
                        ->with('success', 'Invoice deleted successfully.');
    }

    /**
     * Mark invoice as paid
     */
    public function markAsPaid(Invoice $invoice)
    {
        $invoice->update(['status' => 'paid']);

        return redirect()->back()
                        ->with('success', 'Invoice marked as paid.');
    }
}

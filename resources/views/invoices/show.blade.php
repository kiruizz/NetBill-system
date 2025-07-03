@extends('layouts.app')

@section('title', 'Invoice Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Invoice Details</h2>
                <div>
                    @if($invoice->status !== 'paid')
                        <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Invoice
                        </a>
                    @endif
                    <a href="{{ route('payments.record', $invoice->id) }}" class="btn btn-success">
                        <i class="fas fa-credit-card"></i> Record Payment
                    </a>
                    <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Invoices
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Invoice {{ $invoice->invoice_number }}</h5>
                            <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partially_paid' ? 'warning' : ($invoice->status === 'overdue' ? 'danger' : ($invoice->status === 'draft' ? 'secondary' : ($invoice->status === 'cancelled' ? 'dark' : 'info')))) }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <h6>Bill To:</h6>
                                    <address>
                                        <strong>{{ $invoice->client->name }}</strong><br>
                                        @if($invoice->client->address)
                                            {!! nl2br(e($invoice->client->address)) !!}<br>
                                        @endif
                                        @if($invoice->client->phone)
                                            Phone: {{ $invoice->client->phone }}<br>
                                        @endif
                                        Email: {{ $invoice->client->email }}
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td class="text-end"><strong>Invoice Date:</strong></td>
                                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-end"><strong>Due Date:</strong></td>
                                            <td>
                                                {{ $invoice->due_date->format('M d, Y') }}
                                                @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                                    <span class="badge bg-danger ms-2">Overdue</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @if($invoice->servicePlan)
                                            <tr>
                                                <td class="text-end"><strong>Service Plan:</strong></td>
                                                <td>{{ $invoice->servicePlan->name }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>

                            @if($invoice->description)
                                <div class="mb-4">
                                    <h6>Description:</h6>
                                    <p>{{ $invoice->description }}</p>
                                </div>
                            @endif

                            <!-- Invoice Summary -->
                            <div class="row">
                                <div class="col-md-6 offset-md-6">
                                    <table class="table">
                                        <tr>
                                            <td><strong>Subtotal:</strong></td>
                                            <td class="text-end">KES {{ number_format($invoice->subtotal, 2) }}</td>
                                        </tr>
                                        @if($invoice->discount_amount > 0)
                                            <tr>
                                                <td><strong>Discount:</strong></td>
                                                <td class="text-end text-success">-KES {{ number_format($invoice->discount_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        @if($invoice->tax_amount > 0)
                                            <tr>
                                                <td><strong>Tax:</strong></td>
                                                <td class="text-end">KES {{ number_format($invoice->tax_amount, 2) }}</td>
                                            </tr>
                                        @endif
                                        <tr class="table-active">
                                            <td><strong>Total:</strong></td>
                                            <td class="text-end"><strong>KES {{ number_format($invoice->total_amount, 2) }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td><strong>Paid:</strong></td>
                                            <td class="text-end text-success">KES {{ number_format($invoice->payments()->sum('amount'), 2) }}</td>
                                        </tr>
                                        <tr class="{{ $invoice->total_amount - $invoice->payments()->sum('amount') <= 0 ? 'table-success' : 'table-warning' }}">
                                            <td><strong>Balance:</strong></td>
                                            <td class="text-end"><strong>KES {{ number_format($invoice->total_amount - $invoice->payments()->sum('amount'), 2) }}</strong></td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            @if($invoice->notes)
                                <div class="mt-4">
                                    <h6>Notes:</h6>
                                    <div class="alert alert-light">
                                        {{ $invoice->notes }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <!-- Payment History -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Payment History</h6>
                        </div>
                        <div class="card-body">
                            @if($invoice->payments->count() > 0)
                                @foreach($invoice->payments->sortByDesc('payment_date') as $payment)
                                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div>
                                            <div class="fw-bold">KES {{ number_format($payment->amount, 2) }}</div>
                                            <small class="text-muted">
                                                {{ $payment->payment_date->format('M d, Y') }} - 
                                                {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                            </small>
                                        </div>
                                        <a href="{{ route('payments.show', $payment) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <p class="text-muted mb-0">No payments recorded yet.</p>
                            @endif
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                @if($invoice->status !== 'paid')
                                    <a href="{{ route('payments.record', $invoice->id) }}" class="btn btn-success">
                                        <i class="fas fa-credit-card"></i> Record Payment
                                    </a>
                                    <a href="{{ route('invoices.edit', $invoice) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> Edit Invoice
                                    </a>
                                @endif
                                
                                <form action="{{ route('invoices.send', $invoice) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-info w-100">
                                        <i class="fas fa-paper-plane"></i> Send to Client
                                    </button>
                                </form>
                                
                                <a href="{{ route('invoices.pdf', $invoice) }}" class="btn btn-secondary" target="_blank">
                                    <i class="fas fa-download"></i> Download PDF
                                </a>

                                @if($invoice->status !== 'paid')
                                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Are you sure you want to delete this invoice?')">
                                            <i class="fas fa-trash"></i> Delete Invoice
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

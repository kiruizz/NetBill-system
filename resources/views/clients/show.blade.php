
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Client Details</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('clients.edit', $client) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Client
                    </a>
                    <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Clients
                    </a>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Client Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Full Name:</strong>
                                    <p>{{ $client->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Email:</strong>
                                    <p>{{ $client->email }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Phone:</strong>
                                    <p>{{ $client->phone ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>National ID:</strong>
                                    <p>{{ $client->national_id ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Location:</strong>
                                    <p>{{ $client->location ?? 'Not provided' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        <span class="badge bg-{{ $client->status === 'active' ? 'success' : ($client->status === 'suspended' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($client->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            @if($client->address)
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Address:</strong>
                                        <p>{{ $client->address }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Active Subscription -->
                    @if($client->activeSubscription)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Current Subscription</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Service Plan:</strong>
                                        <p>{{ $client->activeSubscription->servicePlan->name }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Monthly Fee:</strong>
                                        <p>KES {{ number_format($client->activeSubscription->servicePlan->price, 2) }}</p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Start Date:</strong>
                                        <p>{{ $client->activeSubscription->start_date->format('M d, Y') }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Status:</strong>
                                        <p>
                                            <span class="badge bg-{{ $client->activeSubscription->status === 'active' ? 'success' : 'warning' }}">
                                                {{ ucfirst($client->activeSubscription->status) }}
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Recent Invoices -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Recent Invoices</h5>
                            <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-sm btn-primary">
                                Create Invoice
                            </a>
                        </div>
                        <div class="card-body">
                            @if($client->invoices->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Invoice #</th>
                                                <th>Amount</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($client->invoices->take(5) as $invoice)
                                                <tr>
                                                    <td>{{ $invoice->invoice_number }}</td>
                                                    <td>KES {{ number_format($invoice->total_amount, 2) }}</td>
                                                    <td>{{ $invoice->issue_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'partially_paid' ? 'info' : 'warning') }}">
                                                            {{ ucwords(str_replace('_', ' ', $invoice->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('invoices.show', $invoice) }}" class="btn btn-sm btn-outline-info">
                                                            View
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted">No invoices found for this client.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Actions -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('invoices.create', ['client_id' => $client->id]) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-file-invoice"></i> Create Invoice
                                </a>
                                <a href="{{ route('payments.create', ['client_id' => $client->id]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-money-bill"></i> Record Payment
                                </a>
                                <a href="{{ route('tickets.create', ['client_id' => $client->id]) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-ticket-alt"></i> Create Ticket
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Account Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Summary</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Invoices:</span>
                                <strong>{{ $client->invoices->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Paid:</span>
                                <strong class="text-success">KES {{ number_format($client->payments->sum('amount'), 2) }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Outstanding:</span>
                                <strong class="text-warning">
                                    KES {{ number_format($client->invoices->where('status', '!=', 'paid')->sum('total_amount') - $client->payments->sum('amount'), 2) }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Member Since:</span>
                                <strong>{{ $client->created_at->format('M Y') }}</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

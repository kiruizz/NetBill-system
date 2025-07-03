
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Invoice Management</h2>
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Invoice
                </a>
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

            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('invoices.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="partially_paid" {{ request('status') === 'partially_paid' ? 'selected' : '' }}>Partially Paid</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="overdue" {{ request('status') === 'overdue' ? 'selected' : '' }}>Overdue</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="client_id" class="form-select">
                                <option value="">All Clients</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="from_date" class="form-control" 
                                   value="{{ request('from_date') }}" placeholder="From Date">
                        </div>
                        <div class="col-md-2">
                            <input type="date" name="to_date" class="form-control" 
                                   value="{{ request('to_date') }}" placeholder="To Date">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Invoices</h5>
                </div>
                <div class="card-body">
                    @if($invoices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Client</th>
                                        <th>Service Plan</th>
                                        <th>Amount</th>
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <strong>{{ $invoice->invoice_number }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('clients.show', $invoice->client) }}" class="text-decoration-none">
                                                    {{ $invoice->client->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($invoice->servicePlan)
                                                    {{ $invoice->servicePlan->name }}
                                                @else
                                                    <span class="text-muted">Custom</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>KES {{ number_format($invoice->total_amount, 2) }}</strong>
                                                @if($invoice->tax_amount > 0)
                                                    <br><small class="text-muted">Tax: KES {{ number_format($invoice->tax_amount, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                            <td>
                                                {{ $invoice->due_date->format('M d, Y') }}
                                                @if($invoice->due_date->isPast() && $invoice->status !== 'paid')
                                                    <br><small class="text-danger">Overdue</small>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'draft' => 'secondary',
                                                        'sent' => 'warning',
                                                        'paid' => 'success',
                                                        'overdue' => 'danger',
                                                        'cancelled' => 'dark',
                                                        'pending' => 'warning',
                                                        'partially_paid' => 'info'
                                                    ];
                                                    $status = $invoice->status;
                                                    if ($invoice->due_date->isPast() && in_array($invoice->status, ['draft', 'sent', 'pending'])) {
                                                        $status = 'overdue';
                                                    }
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$status] ?? 'secondary' }}">
                                                    {{ ucwords(str_replace('_', ' ', $status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('invoices.show', $invoice) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($invoice->status !== 'paid')
                                                        <a href="{{ route('invoices.edit', $invoice) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('invoices.destroy', $invoice) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this invoice?')">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $invoices->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No invoices found</h5>
                            <p class="text-muted">Start by creating invoices for your clients.</p>
                            <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Invoice
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

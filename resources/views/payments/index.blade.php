@extends('layouts.app')

@section('title', 'Payments')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Payment Management</h2>
                <a href="{{ route('payments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Record Payment
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
                    <form method="GET" action="{{ route('payments.index') }}" class="row g-3">
                        <div class="col-md-3">
                            <select name="payment_method" class="form-select">
                                <option value="">All Payment Methods</option>
                                <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>Cash</option>
                                <option value="bank_transfer" {{ request('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="mobile_money" {{ request('payment_method') === 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                <option value="cheque" {{ request('payment_method') === 'cheque' ? 'selected' : '' }}>Cheque</option>
                                <option value="card" {{ request('payment_method') === 'card' ? 'selected' : '' }}>Card</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
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
                    <h5 class="mb-0">All Payments</h5>
                </div>
                <div class="card-body">
                    @if($payments->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Payment Date</th>
                                        <th>Invoice #</th>
                                        <th>Client</th>
                                        <th>Amount</th>
                                        <th>Payment Method</th>
                                        <th>Reference</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($payments as $payment)
                                        <tr>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td>
                                                <a href="{{ route('invoices.show', $payment->invoice) }}" class="text-decoration-none">
                                                    {{ $payment->invoice->invoice_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('clients.show', $payment->invoice->client) }}" class="text-decoration-none">
                                                    {{ $payment->invoice->client->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>KES {{ number_format($payment->amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $methodIcons = [
                                                        'cash' => 'fas fa-money-bill',
                                                        'bank_transfer' => 'fas fa-university',
                                                        'mobile_money' => 'fas fa-mobile-alt',
                                                        'cheque' => 'fas fa-file-alt',
                                                        'card' => 'fas fa-credit-card'
                                                    ];
                                                @endphp
                                                <i class="{{ $methodIcons[$payment->payment_method] ?? 'fas fa-money-bill' }}"></i>
                                                {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                            </td>
                                            <td>{{ $payment->reference_number ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'completed' => 'success',
                                                        'pending' => 'warning',
                                                        'failed' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$payment->status] ?? 'secondary' }}">
                                                    {{ ucfirst($payment->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('payments.show', $payment) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($payment->status !== 'completed')
                                                        <a href="{{ route('payments.edit', $payment) }}" 
                                                           class="btn btn-sm btn-outline-primary" title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @endif
                                                    <form action="{{ route('payments.destroy', $payment) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this payment?')">
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
                            {{ $payments->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-money-bill fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No payments found</h5>
                            <p class="text-muted">Record payments to track client transactions.</p>
                            <a href="{{ route('payments.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Record First Payment
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

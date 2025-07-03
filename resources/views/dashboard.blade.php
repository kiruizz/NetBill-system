@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <!-- Key Metrics Cards -->
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Total Clients</h5>
                        <h2 class="fw-bold text-primary">{{ $stats['total_clients'] ?? 0 }}</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> +{{ $stats['new_clients_this_month'] ?? 0 }} this month
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people text-primary" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100" style="border-left-color: #28a745;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Monthly Revenue</h5>
                        <h2 class="fw-bold text-success">KSH {{ number_format($stats['monthly_revenue'] ?? 0) }}</h2>
                        <small class="text-success">
                            <i class="bi bi-arrow-up"></i> +{{ $stats['revenue_growth'] ?? 0 }}%
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-exchange text-success" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100" style="border-left-color: #ffc107;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Active Devices</h5>
                        <h2 class="fw-bold text-warning">{{ $stats['active_devices'] ?? 0 }}</h2>
                        <small class="text-info">
                            {{ $stats['device_uptime'] ?? 99 }}% uptime
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-router text-warning" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-4">
        <div class="card card-stats h-100" style="border-left-color: #dc3545;">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted">Open Tickets</h5>
                        <h2 class="fw-bold text-danger">{{ $stats['open_tickets'] ?? 0 }}</h2>
                        <small class="text-danger">
                            {{ $stats['urgent_tickets'] ?? 0 }} urgent
                        </small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-headset text-danger" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Invoices -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Invoices</h5>
                <a href="{{ route('invoices.index') }}" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recent_invoices) && count($recent_invoices) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Invoice #</th>
                                    <th>Client</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_invoices as $invoice)
                                <tr>
                                    <td>{{ $invoice->invoice_number }}</td>
                                    <td>{{ $invoice->client->name }}</td>
                                    <td>KSH {{ number_format($invoice->total_amount) }}</td>
                                    <td>
                                        <span class="badge bg-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'overdue' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($invoice->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No recent invoices found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Recent Payments -->
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">Recent Payments</h5>
                <a href="{{ route('payments.index') }}" class="btn btn-sm btn-outline-success">View All</a>
            </div>
            <div class="card-body">
                @if(isset($recent_payments) && count($recent_payments) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Reference</th>
                                    <th>Client</th>
                                    <th>Amount</th>
                                    <th>Method</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recent_payments as $payment)
                                <tr>
                                    <td>{{ $payment->payment_reference }}</td>
                                    <td>{{ $payment->client->name }}</td>
                                    <td>KSH {{ number_format($payment->amount) }}</td>
                                    <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No recent payments found.</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Service Plans Overview -->
    <div class="col-md-8 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Service Plans Overview</h5>
            </div>
            <div class="card-body">
                @if(isset($service_plans) && count($service_plans) > 0)
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Plan Name</th>
                                    <th>Speed</th>
                                    <th>Price</th>
                                    <th>Active Subscriptions</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($service_plans as $plan)
                                <tr>
                                    <td>{{ $plan->name }}</td>
                                    <td>{{ $plan->formatted_speed }}</td>
                                    <td>KSH {{ number_format($plan->monthly_price) }}</td>
                                    <td>{{ $plan->active_subscriptions_count ?? 0 }}</td>
                                    <td>
                                        <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($plan->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-muted">No service plans found.</p>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @can('manage-clients')
                    <a href="{{ route('clients.create') }}" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i> Add New Client
                    </a>
                    @endcan
                    
                    @can('manage-devices')
                    <a href="{{ route('devices.create') }}" class="btn btn-info">
                        <i class="bi bi-router"></i> Register Device
                    </a>
                    @endcan
                    
                    @can('manage-invoices')
                    <a href="{{ route('invoices.create') }}" class="btn btn-warning">
                        <i class="bi bi-file-earmark-plus"></i> Create Invoice
                    </a>
                    @endcan
                    
                    @can('manage-payments')
                    <a href="{{ route('payments.record') }}" class="btn btn-success">
                        <i class="bi bi-credit-card"></i> Record Payment
                    </a>
                    @endcan
                    
                    @can('manage-tickets')
                    <a href="{{ route('tickets.create') }}" class="btn btn-danger">
                        <i class="bi bi-headset"></i> Create Ticket
                    </a>
                    @endcan
                    
                    @can('view-reports')
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="bi bi-graph-up"></i> View Reports
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <h6 class="text-muted">Network Status</h6>
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle"></i> Online
                        </span>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Database</h6>
                        <span class="badge bg-success fs-6">
                            <i class="bi bi-check-circle"></i> Connected
                        </span>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">Last Backup</h6>
                        <span class="text-muted">{{ $stats['last_backup'] ?? 'Not configured' }}</span>
                    </div>
                    <div class="col-md-3">
                        <h6 class="text-muted">System Load</h6>
                        <span class="text-success">{{ $stats['system_load'] ?? 'Low' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-refresh dashboard every 5 minutes
    setTimeout(function() {
        window.location.reload();
    }, 300000);
</script>
@endpush

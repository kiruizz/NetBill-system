@extends('layouts.app')

@section('title', 'Revenue Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Revenue Report</h2>
                <div>
                    <a href="{{ route('reports.export', 'revenue') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
                       class="btn btn-success">
                        <i class="fas fa-download"></i> Export CSV
                    </a>
                    <a href="{{ route('reports.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Reports
                    </a>
                </div>
            </div>

            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Filter Options</h5>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('reports.revenue') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" name="start_date" id="start_date" 
                                       class="form-control" value="{{ $startDate }}">
                            </div>
                            <div class="col-md-4">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" name="end_date" id="end_date" 
                                       class="form-control" value="{{ $endDate }}">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter"></i> Apply Filter
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Invoiced</h5>
                                    <h3>KES {{ number_format($summary['total_invoiced'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-file-invoice fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Paid</h5>
                                    <h3>KES {{ number_format($summary['total_paid'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-money-bill-wave fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Outstanding</h5>
                                    <h3>KES {{ number_format($summary['total_outstanding'], 2) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-exclamation-triangle fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revenue Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Revenue Details ({{ $startDate }} to {{ $endDate }})</h5>
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
                                        <th>Issue Date</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Paid</th>
                                        <th>Balance</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoices as $invoice)
                                        <tr>
                                            <td>
                                                <strong>INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                            </td>
                                            <td>
                                                <a href="{{ route('clients.show', $invoice->client) }}">
                                                    {{ $invoice->client->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @if($invoice->servicePlan)
                                                    {{ $invoice->servicePlan->name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $invoice->issue_date->format('d M Y') }}</td>
                                            <td>{{ $invoice->due_date->format('d M Y') }}</td>
                                            <td>
                                                <strong>KES {{ number_format($invoice->total_amount, 2) }}</strong>
                                            </td>
                                            <td>
                                                KES {{ number_format($invoice->payments()->sum('amount'), 2) }}
                                            </td>
                                            <td>
                                                @php
                                                    $balance = $invoice->total_amount - $invoice->payments()->sum('amount');
                                                @endphp
                                                <span class="{{ $balance <= 0 ? 'text-success' : 'text-warning' }}">
                                                    KES {{ number_format($balance, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($invoice->status === 'paid')
                                                    <span class="badge bg-success">Paid</span>
                                                @elseif($invoice->status === 'partially_paid')
                                                    <span class="badge bg-warning">Partially Paid</span>
                                                @elseif($invoice->status === 'overdue')
                                                    <span class="badge bg-danger">Overdue</span>
                                                @else
                                                    <span class="badge bg-secondary">{{ ucfirst($invoice->status) }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('invoices.show', $invoice) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if($invoice->status !== 'paid')
                                                        <a href="{{ route('payments.record', $invoice->id) }}" 
                                                           class="btn btn-sm btn-outline-success" title="Record Payment">
                                                            <i class="fas fa-money-bill"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $invoices->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No revenue data found</h5>
                            <p class="text-muted">No invoices found for the selected date range.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

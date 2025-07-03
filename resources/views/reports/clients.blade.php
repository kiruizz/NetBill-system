@extends('layouts.app')

@section('title', 'Clients Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Clients Report</h2>
                <div>
                    <a href="{{ route('reports.export', 'clients') }}" class="btn btn-success">
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
                    <form method="GET" action="{{ route('reports.clients') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">All Statuses</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="client_type" class="form-label">Client Type</label>
                                <select name="client_type" id="client_type" class="form-select">
                                    <option value="">All Types</option>
                                    <option value="individual" {{ request('client_type') == 'individual' ? 'selected' : '' }}>Individual</option>
                                    <option value="business" {{ request('client_type') == 'business' ? 'selected' : '' }}>Business</option>
                                    <option value="corporate" {{ request('client_type') == 'corporate' ? 'selected' : '' }}>Corporate</option>
                                </select>
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
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Total Clients</h5>
                                    <h3>{{ $summary['total_clients'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Active</h5>
                                    <h3>{{ $summary['active_clients'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-check fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Suspended</h5>
                                    <h3>{{ $summary['suspended_clients'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-times fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-secondary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Inactive</h5>
                                    <h3>{{ $summary['inactive_clients'] }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-user-slash fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Client Details -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Client Details</h5>
                </div>
                <div class="card-body">
                    @if($clients->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Contact</th>
                                        <th>Type</th>
                                        <th>Status</th>
                                        <th>Service Plan</th>
                                        <th>Connection Date</th>
                                        <th>Account Balance</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($clients as $client)
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong>{{ $client->name }}</strong>
                                                    @if($client->business_name)
                                                        <br><small class="text-muted">{{ $client->business_name }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td>
                                                <div>
                                                    <small><i class="fas fa-envelope"></i> {{ $client->email }}</small><br>
                                                    <small><i class="fas fa-phone"></i> {{ $client->phone }}</small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($client->client_type) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($client->status === 'active')
                                                    <span class="badge bg-success">Active</span>
                                                @elseif($client->status === 'suspended')
                                                    <span class="badge bg-warning">Suspended</span>
                                                @else
                                                    <span class="badge bg-secondary">Inactive</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->activeSubscription && $client->activeSubscription->servicePlan)
                                                    <div>
                                                        <strong>{{ $client->activeSubscription->servicePlan->name }}</strong>
                                                        <br><small class="text-muted">
                                                            KES {{ number_format($client->activeSubscription->servicePlan->price, 2) }}/{{ $client->activeSubscription->servicePlan->billing_cycle }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="text-muted">No Active Plan</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($client->connection_date)
                                                    {{ $client->connection_date->format('d M Y') }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @php
                                                    $balance = $client->account_balance ?? 0;
                                                @endphp
                                                <span class="{{ $balance < 0 ? 'text-danger' : ($balance > 0 ? 'text-success' : 'text-muted') }}">
                                                    KES {{ number_format($balance, 2) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('clients.show', $client) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('clients.edit', $client) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('clients.invoices', $client) }}" 
                                                       class="btn btn-sm btn-outline-success" title="Invoices">
                                                        <i class="fas fa-file-invoice"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-3">
                            {{ $clients->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-users fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No clients found</h5>
                            <p class="text-muted">No clients match the selected criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

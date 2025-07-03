@extends('layouts.app')

@section('title', 'Service Plan Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Service Plan Details</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('service-plans.edit', $servicePlan) }}" class="btn btn-primary">
                        <i class="fas fa-edit"></i> Edit Plan
                    </a>
                    <a href="{{ route('service-plans.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Plans
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
                <!-- Plan Information -->
                <div class="col-md-8">
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Plan Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Plan Name:</strong>
                                    <p class="text-primary fs-4">{{ $servicePlan->name }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Status:</strong>
                                    <p>
                                        <span class="badge bg-{{ $servicePlan->status === 'active' ? 'success' : 'danger' }} fs-6">
                                            {{ ucfirst($servicePlan->status) }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                            
                            @if($servicePlan->description)
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Description:</strong>
                                        <p>{{ $servicePlan->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Download Speed:</strong>
                                    <p class="text-success">
                                        <i class="fas fa-download"></i> {{ $servicePlan->speed_download }} Mbps
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Upload Speed:</strong>
                                    <p class="text-info">
                                        <i class="fas fa-upload"></i> {{ $servicePlan->speed_upload }} Mbps
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Data Limit:</strong>
                                    <p>
                                        @if($servicePlan->data_limit)
                                            {{ $servicePlan->data_limit }} GB
                                        @else
                                            <span class="badge bg-success">Unlimited</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Billing Cycle:</strong>
                                    <p>
                                        <span class="badge bg-info">
                                            {{ ucfirst($servicePlan->billing_cycle) }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <strong>Price:</strong>
                                    <p class="text-success fs-5">KES {{ number_format($servicePlan->price, 2) }}</p>
                                    <small class="text-muted">per {{ $servicePlan->billing_cycle }}</small>
                                </div>
                                <div class="col-md-6">
                                    <strong>Setup Fee:</strong>
                                    <p>
                                        @if($servicePlan->setup_fee > 0)
                                            KES {{ number_format($servicePlan->setup_fee, 2) }}
                                        @else
                                            <span class="text-muted">No setup fee</span>
                                        @endif
                                    </p>
                                </div>
                            </div>

                            @if($servicePlan->features)
                                <div class="row">
                                    <div class="col-12">
                                        <strong>Features:</strong>
                                        <div class="mt-2">
                                            @php
                                                $features = is_string($servicePlan->features) ? json_decode($servicePlan->features, true) : $servicePlan->features;
                                            @endphp
                                            @if($features && is_array($features))
                                                @foreach($features as $key => $value)
                                                    <span class="badge bg-secondary me-1 mb-1">
                                                        {{ ucwords(str_replace('_', ' ', $key)) }}: 
                                                        {{ is_bool($value) ? ($value ? 'Yes' : 'No') : $value }}
                                                    </span>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Active Subscriptions -->
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Active Subscriptions</h5>
                            <span class="badge bg-primary">{{ $servicePlan->subscriptions->count() }} clients</span>
                        </div>
                        <div class="card-body">
                            @if($servicePlan->subscriptions->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr>
                                                <th>Client</th>
                                                <th>Start Date</th>
                                                <th>Status</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($servicePlan->subscriptions->take(10) as $subscription)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('clients.show', $subscription->client) }}" class="text-decoration-none">
                                                            {{ $subscription->client->name }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $subscription->start_date->format('M d, Y') }}</td>
                                                    <td>
                                                        <span class="badge bg-{{ $subscription->status === 'active' ? 'success' : 'warning' }}">
                                                            {{ ucfirst($subscription->status) }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('clients.show', $subscription->client) }}" class="btn btn-sm btn-outline-info">
                                                            View Client
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                @if($servicePlan->subscriptions->count() > 10)
                                    <p class="text-muted mt-2">Showing first 10 subscriptions...</p>
                                @endif
                            @else
                                <p class="text-muted">No active subscriptions for this plan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-md-4">
                    <!-- Quick Stats -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Plan Statistics</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Total Subscribers:</span>
                                <strong>{{ $servicePlan->subscriptions->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Active Subscribers:</span>
                                <strong class="text-success">{{ $servicePlan->subscriptions->where('status', 'active')->count() }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Monthly Revenue:</span>
                                <strong class="text-success">
                                    KES {{ number_format($servicePlan->subscriptions->where('status', 'active')->count() * $servicePlan->price, 2) }}
                                </strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Created:</span>
                                <strong>{{ $servicePlan->created_at->format('M d, Y') }}</strong>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('service-plans.edit', $servicePlan) }}" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i> Edit Plan
                                </a>
                                <a href="{{ route('clients.create', ['service_plan_id' => $servicePlan->id]) }}" class="btn btn-success btn-sm">
                                    <i class="fas fa-user-plus"></i> Add Client to Plan
                                </a>
                                @if($servicePlan->subscriptions->count() === 0)
                                    <form action="{{ route('service-plans.destroy', $servicePlan) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100" 
                                                onclick="return confirm('Are you sure you want to delete this service plan?')">
                                            <i class="fas fa-trash"></i> Delete Plan
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

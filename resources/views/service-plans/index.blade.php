@extends('layouts.app')

@section('title', 'Service Plans')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Service Plans</h2>
                <a href="{{ route('service-plans.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Plan
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

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Service Plans</h5>
                </div>
                <div class="card-body">
                    @if($servicePlans->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Plan Name</th>
                                        <th>Speed</th>
                                        <th>Data Limit</th>
                                        <th>Price</th>
                                        <th>Billing Cycle</th>
                                        <th>Subscribers</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($servicePlans as $plan)
                                        <tr>
                                            <td>
                                                <strong>{{ $plan->name }}</strong>
                                                @if($plan->description)
                                                    <br><small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex flex-column">
                                                    <small><i class="fas fa-download text-success"></i> {{ $plan->speed_download }} Mbps</small>
                                                    <small><i class="fas fa-upload text-info"></i> {{ $plan->speed_upload }} Mbps</small>
                                                </div>
                                            </td>
                                            <td>
                                                @if($plan->data_limit)
                                                    {{ $plan->data_limit }} GB
                                                @else
                                                    <span class="badge bg-success">Unlimited</span>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>KES {{ number_format($plan->price, 2) }}</strong>
                                                @if($plan->setup_fee)
                                                    <br><small class="text-muted">Setup: KES {{ number_format($plan->setup_fee, 2) }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ ucfirst($plan->billing_cycle) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-primary">
                                                    {{ $plan->subscriptions_count }} clients
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $plan->status === 'active' ? 'success' : 'danger' }}">
                                                    {{ ucfirst($plan->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('service-plans.show', $plan) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('service-plans.edit', $plan) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('service-plans.destroy', $plan) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this service plan?')">
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
                            {{ $servicePlans->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-wifi fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No service plans found</h5>
                            <p class="text-muted">Create service plans to offer to your clients.</p>
                            <a href="{{ route('service-plans.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Plan
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

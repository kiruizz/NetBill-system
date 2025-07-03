@extends('layouts.app')

@section('title', 'Reports & Analytics')

@section('actions')
<div class="btn-group" role="group">
    <a href="{{ route('reports.revenue') }}" class="btn btn-outline-primary">
        <i class="bi bi-currency-exchange"></i> Revenue Report
    </a>
    <a href="{{ route('reports.clients') }}" class="btn btn-outline-info">
        <i class="bi bi-people"></i> Clients Report
    </a>
    <a href="{{ route('reports.usage') }}" class="btn btn-outline-success">
        <i class="bi bi-graph-up"></i> Usage Report
    </a>
</div>
@endsection

@section('content')
<!-- Key Metrics -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-left-primary h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">This Month Revenue</h6>
                        <h4 class="text-primary">KSH {{ number_format($revenue['current_month']) }}</h4>
                        @if($revenue['last_month'] > 0)
                            @php
                                $growth = (($revenue['current_month'] - $revenue['last_month']) / $revenue['last_month']) * 100;
                            @endphp
                            <small class="text-{{ $growth >= 0 ? 'success' : 'danger' }}">
                                <i class="bi bi-arrow-{{ $growth >= 0 ? 'up' : 'down' }}"></i>
                                {{ number_format(abs($growth), 1) }}% from last month
                            </small>
                        @endif
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-exchange text-primary" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-success h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Total Collected</h6>
                        <h4 class="text-success">KSH {{ number_format($revenue['total_collected']) }}</h4>
                        <small class="text-muted">All-time payments</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle text-success" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-warning h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Outstanding</h6>
                        <h4 class="text-warning">KSH {{ number_format($revenue['total_outstanding']) }}</h4>
                        <small class="text-muted">Pending payments</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock text-warning" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card border-left-info h-100">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted">Active Clients</h6>
                        <h4 class="text-info">{{ number_format($clientStats['active']) }}</h4>
                        <small class="text-muted">{{ $clientStats['new_this_month'] }} new this month</small>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-people text-info" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Monthly Revenue Chart -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Monthly Revenue Trend (Last 12 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Top Service Plans -->
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Top Service Plans by Revenue</h5>
            </div>
            <div class="card-body">
                @if(count($topPlans) > 0)
                    @foreach($topPlans as $plan)
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <strong>{{ $plan->name }}</strong>
                        </div>
                        <div>
                            <span class="badge bg-primary">KSH {{ number_format($plan->total_revenue) }}</span>
                        </div>
                    </div>
                    @endforeach
                @else
                    <p class="text-muted">No revenue data available</p>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Revenue Reports</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Generate detailed revenue and billing reports.</p>
                <a href="{{ route('reports.revenue') }}" class="btn btn-primary">
                    <i class="bi bi-file-earmark-bar-graph"></i> View Revenue Report
                </a>
                <a href="{{ route('reports.export', 'revenue') }}" class="btn btn-outline-primary ms-2">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Client Reports</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Analyze client demographics and subscriptions.</p>
                <a href="{{ route('reports.clients') }}" class="btn btn-info">
                    <i class="bi bi-people"></i> View Client Report
                </a>
                <a href="{{ route('reports.export', 'clients') }}" class="btn btn-outline-info ms-2">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Usage Reports</h5>
            </div>
            <div class="card-body">
                <p class="card-text">Monitor network usage and data consumption.</p>
                <a href="{{ route('reports.usage') }}" class="btn btn-success">
                    <i class="bi bi-graph-up"></i> View Usage Report
                </a>
                <a href="{{ route('reports.export', 'usage') }}" class="btn btn-outline-success ms-2">
                    <i class="bi bi-download"></i> Export CSV
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: {!! json_encode(array_column($monthlyRevenue, 'month')) !!},
        datasets: [{
            label: 'Monthly Revenue (KSH)',
            data: {!! json_encode(array_column($monthlyRevenue, 'revenue')) !!},
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'KSH ' + value.toLocaleString();
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Revenue: KSH ' + context.parsed.y.toLocaleString();
                    }
                }
            }
        }
    }
});
</script>
@endpush

@push('styles')
<style>
.card {
    box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
    border: 1px solid #e3e6f0;
}

.border-left-primary {
    border-left: 0.25rem solid #4e73df !important;
}

.border-left-success {
    border-left: 0.25rem solid #1cc88a !important;
}

.border-left-warning {
    border-left: 0.25rem solid #f6c23e !important;
}

.border-left-info {
    border-left: 0.25rem solid #36b9cc !important;
}
</style>
@endpush

@extends('layouts.app')

@section('title', 'Usage Report')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Network Usage Report</h2>
                <div>
                    <a href="{{ route('reports.export', 'usage') }}?start_date={{ $startDate }}&end_date={{ $endDate }}" 
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
                    <form method="GET" action="{{ route('reports.usage') }}">
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
                                    <h5>Total Usage</h5>
                                    <h3>{{ number_format($summary['total_usage_gb'], 2) }} GB</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-database fa-2x"></i>
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
                                    <h5>Daily Average</h5>
                                    <h3>{{ number_format($summary['average_daily_usage'], 2) }} GB</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-chart-line fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h5>Active Users</h5>
                                    <h3>{{ count($summary['top_users']) }}</h3>
                                </div>
                                <div class="align-self-center">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Usage Details -->
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Usage Details ({{ $startDate }} to {{ $endDate }})</h5>
                        </div>
                        <div class="card-body">
                            @if($usageData->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead class="table-dark">
                                            <tr>
                                                <th>Date</th>
                                                <th>Client</th>
                                                <th>Service Plan</th>
                                                <th>Download</th>
                                                <th>Upload</th>
                                                <th>Total</th>
                                                <th>Session</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($usageData as $usage)
                                                <tr>
                                                    <td>{{ $usage->usage_date->format('d M Y') }}</td>
                                                    <td>
                                                        <a href="{{ route('clients.show', $usage->client) }}">
                                                            {{ $usage->client->name }}
                                                        </a>
                                                    </td>
                                                    <td>
                                                        @if($usage->subscription && $usage->subscription->servicePlan)
                                                            {{ $usage->subscription->servicePlan->name }}
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-info">
                                                            {{ number_format($usage->download_gb, 2) }} GB
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-success">
                                                            {{ number_format($usage->upload_gb, 2) }} GB
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <strong>{{ number_format($usage->total_gb, 2) }} GB</strong>
                                                    </td>
                                                    <td>
                                                        @if($usage->session_duration_hours)
                                                            {{ number_format($usage->session_duration_hours, 1) }}h
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Pagination -->
                                <div class="d-flex justify-content-center mt-3">
                                    {{ $usageData->appends(request()->query())->links() }}
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">No usage data found</h5>
                                    <p class="text-muted">No network usage data found for the selected date range.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Top Users -->
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Top Users</h5>
                        </div>
                        <div class="card-body">
                            @if($summary['top_users']->count() > 0)
                                <div class="list-group list-group-flush">
                                    @foreach($summary['top_users'] as $index => $topUser)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">
                                                    <a href="{{ route('clients.show', $topUser->client) }}">
                                                        {{ $topUser->client->name }}
                                                    </a>
                                                </h6>
                                                <small class="text-muted">
                                                    #{{ $index + 1 }} User
                                                </small>
                                            </div>
                                            <div class="text-end">
                                                <strong>{{ number_format($topUser->total_usage / 1024, 2) }} GB</strong>
                                                <br><small class="text-muted">Total Usage</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-3">
                                    <i class="fas fa-user-slash fa-2x text-muted mb-2"></i>
                                    <p class="text-muted mb-0">No usage data available</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Usage by Date Chart (placeholder) -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h5 class="mb-0">Usage Trend</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-3">
                                <i class="fas fa-chart-area fa-3x text-muted mb-3"></i>
                                <p class="text-muted">Usage trend chart coming soon...</p>
                                <small class="text-muted">This will show daily usage patterns</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

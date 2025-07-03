@extends('layouts.app')

@section('title', 'Devices')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Device Management</h2>
                <a href="{{ route('devices.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Device
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
                    <h5 class="mb-0">All Devices</h5>
                </div>
                <div class="card-body">
                    @if($devices->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Name</th>
                                        <th>Type</th>
                                        <th>Model</th>
                                        <th>IP Address</th>
                                        <th>Client</th>
                                        <th>Location</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($devices as $device)
                                        <tr>
                                            <td>
                                                <strong>{{ $device->name }}</strong>
                                                @if($device->serial_number)
                                                    <br><small class="text-muted">SN: {{ $device->serial_number }}</small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary">
                                                    {{ ucwords(str_replace('_', ' ', $device->device_type)) }}
                                                </span>
                                            </td>
                                            <td>{{ $device->model ?? 'N/A' }}</td>
                                            <td>{{ $device->ip_address ?? 'N/A' }}</td>
                                            <td>
                                                @if($device->client)
                                                    <a href="{{ route('clients.show', $device->client) }}" class="text-decoration-none">
                                                        {{ $device->client->name }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $device->location ?? 'N/A' }}</td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'active' => 'success',
                                                        'inactive' => 'secondary',
                                                        'maintenance' => 'warning',
                                                        'faulty' => 'danger'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$device->status] ?? 'secondary' }}">
                                                    {{ ucfirst($device->status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('devices.show', $device) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('devices.edit', $device) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('devices.destroy', $device) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this device?')">
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
                            {{ $devices->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-server fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No devices found</h5>
                            <p class="text-muted">Start by adding network devices to the system.</p>
                            <a href="{{ route('devices.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Add First Device
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

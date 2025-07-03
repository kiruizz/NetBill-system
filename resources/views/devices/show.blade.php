@extends('layouts.app')

@section('title', 'Device Details')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Device Details</h2>
                <div>
                    <a href="{{ route('devices.edit', $device) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Edit Device
                    </a>
                    <a href="{{ route('devices.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Devices
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
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Device Information</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Device Name:</strong></td>
                                    <td>{{ $device->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Type:</strong></td>
                                    <td>
                                        <span class="badge bg-secondary">
                                            {{ ucwords(str_replace('_', ' ', $device->device_type)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Model:</strong></td>
                                    <td>{{ $device->model ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Serial Number:</strong></td>
                                    <td>{{ $device->serial_number ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>MAC Address:</strong></td>
                                    <td>{{ $device->mac_address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>IP Address:</strong></td>
                                    <td>{{ $device->ip_address ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Location:</strong></td>
                                    <td>{{ $device->location ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($device->status === 'active')
                                            <span class="badge bg-success">Active</span>
                                        @elseif($device->status === 'inactive')
                                            <span class="badge bg-secondary">Inactive</span>
                                        @elseif($device->status === 'maintenance')
                                            <span class="badge bg-warning">Maintenance</span>
                                        @else
                                            <span class="badge bg-danger">Faulty</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Assignment & Warranty</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Assigned Client:</strong></td>
                                    <td>
                                        @if($device->client)
                                            <a href="{{ route('clients.show', $device->client) }}" class="text-decoration-none">
                                                {{ $device->client->name }}
                                            </a>
                                        @else
                                            <span class="text-muted">Unassigned</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Installation Date:</strong></td>
                                    <td>{{ $device->installation_date ? $device->installation_date->format('d M Y') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Warranty Expiry:</strong></td>
                                    <td>
                                        @if($device->warranty_expiry)
                                            {{ $device->warranty_expiry->format('d M Y') }}
                                            @if($device->warranty_expiry->isPast())
                                                <span class="badge bg-danger ms-2">Expired</span>
                                            @else
                                                <span class="badge bg-success ms-2">Valid</span>
                                            @endif
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $device->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $device->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                            </table>

                            @if(!$device->client)
                                <div class="mt-3">
                                    <form action="{{ route('devices.assign', $device) }}" method="POST" class="d-inline">
                                        @csrf
                                        <div class="input-group">
                                            <select name="client_id" class="form-select" required>
                                                <option value="">Select client to assign</option>
                                                @foreach(\App\Models\Client::where('status', 'active')->orderBy('name')->get() as $client)
                                                    <option value="{{ $client->id }}">{{ $client->name }}</option>
                                                @endforeach
                                            </select>
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-link"></i> Assign
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            @if($device->notes)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Notes</h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $device->notes }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Device Actions -->
            <div class="row mt-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex gap-2 flex-wrap">
                                <a href="{{ route('devices.edit', $device) }}" class="btn btn-warning">
                                    <i class="fas fa-edit"></i> Edit Device
                                </a>
                                
                                @if($device->client)
                                    <form action="{{ route('devices.assign', $device) }}" method="POST" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="client_id" value="">
                                        <button type="submit" class="btn btn-secondary" onclick="return confirm('Are you sure you want to unassign this device?')">
                                            <i class="fas fa-unlink"></i> Unassign Device
                                        </button>
                                    </form>
                                @endif

                                <form action="{{ route('devices.destroy', $device) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this device? This action cannot be undone.')">
                                        <i class="fas fa-trash"></i> Delete Device
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Edit Device')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Edit Device</h2>
                <a href="{{ route('devices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Devices
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Device Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('devices.update', $device) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $device->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="device_type" class="form-label">Device Type <span class="text-danger">*</span></label>
                                    <select class="form-select @error('device_type') is-invalid @enderror" id="device_type" name="device_type" required>
                                        <option value="">Select device type</option>
                                        <option value="router" {{ old('device_type', $device->device_type) == 'router' ? 'selected' : '' }}>Router</option>
                                        <option value="switch" {{ old('device_type', $device->device_type) == 'switch' ? 'selected' : '' }}>Switch</option>
                                        <option value="access_point" {{ old('device_type', $device->device_type) == 'access_point' ? 'selected' : '' }}>Access Point</option>
                                        <option value="firewall" {{ old('device_type', $device->device_type) == 'firewall' ? 'selected' : '' }}>Firewall</option>
                                        <option value="server" {{ old('device_type', $device->device_type) == 'server' ? 'selected' : '' }}>Server</option>
                                        <option value="other" {{ old('device_type', $device->device_type) == 'other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('device_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control @error('model') is-invalid @enderror" 
                                           id="model" name="model" value="{{ old('model', $device->model) }}">
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number', $device->serial_number) }}">
                                    @error('serial_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="mac_address" class="form-label">MAC Address</label>
                                    <input type="text" class="form-control @error('mac_address') is-invalid @enderror" 
                                           id="mac_address" name="mac_address" value="{{ old('mac_address', $device->mac_address) }}"
                                           placeholder="00:00:00:00:00:00">
                                    @error('mac_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ip_address" class="form-label">IP Address</label>
                                    <input type="text" class="form-control @error('ip_address') is-invalid @enderror" 
                                           id="ip_address" name="ip_address" value="{{ old('ip_address', $device->ip_address) }}"
                                           placeholder="192.168.1.1">
                                    @error('ip_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" 
                                           id="location" name="location" value="{{ old('location', $device->location) }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Assigned Client</label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                        <option value="">No client assigned</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', $device->client_id) == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="installation_date" class="form-label">Installation Date</label>
                                    <input type="date" class="form-control @error('installation_date') is-invalid @enderror" 
                                           id="installation_date" name="installation_date" value="{{ old('installation_date', $device->installation_date ? $device->installation_date->format('Y-m-d') : '') }}">
                                    @error('installation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                           id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry', $device->warranty_expiry ? $device->warranty_expiry->format('Y-m-d') : '') }}">
                                    @error('warranty_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                        <option value="active" {{ old('status', $device->status) == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status', $device->status) == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status', $device->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="faulty" {{ old('status', $device->status) == 'faulty' ? 'selected' : '' }}>Faulty</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes', $device->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Device
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-format MAC address
    document.getElementById('mac_address').addEventListener('input', function(e) {
        let value = e.target.value.replace(/[^a-fA-F0-9]/g, '');
        let formatted = value.match(/.{1,2}/g)?.join(':') || value;
        if (formatted.length > 17) formatted = formatted.substring(0, 17);
        e.target.value = formatted.toUpperCase();
    });
</script>
@endpush

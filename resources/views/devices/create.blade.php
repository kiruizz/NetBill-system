@extends('layouts.app')

@section('title', 'Register Device')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Register New Device</h2>
                <a href="{{ route('devices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Devices
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Device Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('devices.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Device Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
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
                                        <option value="router" {{ old('device_type') == 'router' ? 'selected' : '' }}>Router</option>
                                        <option value="switch" {{ old('device_type') == 'switch' ? 'selected' : '' }}>Switch</option>
                                        <option value="access_point" {{ old('device_type') == 'access_point' ? 'selected' : '' }}>Access Point</option>
                                        <option value="firewall" {{ old('device_type') == 'firewall' ? 'selected' : '' }}>Firewall</option>
                                        <option value="server" {{ old('device_type') == 'server' ? 'selected' : '' }}>Server</option>
                                        <option value="other" {{ old('device_type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                           id="model" name="model" value="{{ old('model') }}">
                                    @error('model')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="serial_number" class="form-label">Serial Number</label>
                                    <input type="text" class="form-control @error('serial_number') is-invalid @enderror" 
                                           id="serial_number" name="serial_number" value="{{ old('serial_number') }}">
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
                                           id="mac_address" name="mac_address" value="{{ old('mac_address') }}"
                                           placeholder="AA:BB:CC:DD:EE:FF">
                                    @error('mac_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="ip_address" class="form-label">IP Address</label>
                                    <input type="text" class="form-control @error('ip_address') is-invalid @enderror" 
                                           id="ip_address" name="ip_address" value="{{ old('ip_address') }}"
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
                                           id="location" name="location" value="{{ old('location') }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Assign to Client</label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id">
                                        <option value="">Select client (optional)</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
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
                                           id="installation_date" name="installation_date" value="{{ old('installation_date') }}">
                                    @error('installation_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="warranty_expiry" class="form-label">Warranty Expiry</label>
                                    <input type="date" class="form-control @error('warranty_expiry') is-invalid @enderror" 
                                           id="warranty_expiry" name="warranty_expiry" value="{{ old('warranty_expiry') }}">
                                    @error('warranty_expiry')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                        <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                                        <option value="faulty" {{ old('status') == 'faulty' ? 'selected' : '' }}>Faulty</option>
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
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('devices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Register Device
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
        let value = e.target.value.replace(/[^a-fA-F0-9]/g, '').toUpperCase();
        if (value.length > 0) {
            value = value.match(/.{1,2}/g).join(':');
            if (value.length > 17) {
                value = value.substring(0, 17);
            }
        }
        e.target.value = value;
    });
</script>
@endpush

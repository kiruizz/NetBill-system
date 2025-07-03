@extends('layouts.app')

@section('title', 'Create Service Plan')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Create New Service Plan</h2>
                <a href="{{ route('service-plans.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Service Plans
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Service Plan Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('service-plans.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Plan Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="billing_cycle" class="form-label">Billing Cycle <span class="text-danger">*</span></label>
                                    <select class="form-select @error('billing_cycle') is-invalid @enderror" id="billing_cycle" name="billing_cycle" required>
                                        <option value="">Select billing cycle</option>
                                        <option value="monthly" {{ old('billing_cycle') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                        <option value="quarterly" {{ old('billing_cycle') == 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                        <option value="yearly" {{ old('billing_cycle') == 'yearly' ? 'selected' : '' }}>Yearly</option>
                                    </select>
                                    @error('billing_cycle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speed_download" class="form-label">Download Speed (Mbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('speed_download') is-invalid @enderror" 
                                           id="speed_download" name="speed_download" value="{{ old('speed_download') }}" min="1" required>
                                    @error('speed_download')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="speed_upload" class="form-label">Upload Speed (Mbps) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('speed_upload') is-invalid @enderror" 
                                           id="speed_upload" name="speed_upload" value="{{ old('speed_upload') }}" min="1" required>
                                    @error('speed_upload')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="data_limit" class="form-label">Data Limit (GB)</label>
                                    <input type="number" class="form-control @error('data_limit') is-invalid @enderror" 
                                           id="data_limit" name="data_limit" value="{{ old('data_limit') }}" min="0">
                                    <small class="form-text text-muted">Leave empty for unlimited</small>
                                    @error('data_limit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price (KES) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" 
                                           id="price" name="price" value="{{ old('price') }}" min="0" required>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="setup_fee" class="form-label">Setup Fee (KES)</label>
                                    <input type="number" step="0.01" class="form-control @error('setup_fee') is-invalid @enderror" 
                                           id="setup_fee" name="setup_fee" value="{{ old('setup_fee', 0) }}" min="0">
                                    @error('setup_fee')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="features" class="form-label">Features (JSON)</label>
                            <textarea class="form-control @error('features') is-invalid @enderror" 
                                      id="features" name="features" rows="4" 
                                      placeholder='{"bandwidth_guarantee": true, "static_ip": false, "support_level": "standard"}'>{{ old('features') }}</textarea>
                            <small class="form-text text-muted">Enter features in JSON format</small>
                            @error('features')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('service-plans.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Service Plan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

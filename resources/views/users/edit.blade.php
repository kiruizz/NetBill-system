@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit User</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('users.update', $user) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $user->name) }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           name="email" 
                                           id="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           value="{{ old('email', $user->email) }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password" class="form-label">New Password</label>
                                    <input type="password" 
                                           name="password" 
                                           id="password" 
                                           class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Leave blank to keep current password.</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm New Password</label>
                                    <input type="password" 
                                           name="password_confirmation" 
                                           id="password_confirmation" 
                                           class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Assign Roles</label>
                            <div class="row">
                                @foreach($roles as $role)
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            @php
                                                $isChecked = in_array($role->name, old('roles', $user->roles->pluck('name')->toArray()));
                                                $isDisabled = $user->id === auth()->id() && $role->name === 'Super Admin' && \App\Models\User::role('Super Admin')->count() <= 1;
                                            @endphp
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="roles[]" 
                                                   value="{{ $role->name }}" 
                                                   id="role_{{ $role->id }}"
                                                   {{ $isChecked ? 'checked' : '' }}
                                                   {{ $isDisabled ? 'disabled' : '' }}>
                                            <label class="form-check-label" for="role_{{ $role->id }}">
                                                {{ $role->name }}
                                                @if($role->name === 'Super Admin')
                                                    <span class="badge bg-danger ms-1">Full Access</span>
                                                    @if($isDisabled)
                                                        <span class="badge bg-warning ms-1">Cannot Remove</span>
                                                    @endif
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            @error('roles')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                            @if($user->id === auth()->id())
                                <div class="form-text">
                                    <i class="fas fa-info-circle"></i>
                                    You cannot remove the Super Admin role from your own account if you're the last Super Admin.
                                </div>
                            @endif
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Role Information:</strong>
                            <ul class="mb-0 mt-2">
                                <li><strong>Super Admin:</strong> Full system access and user management</li>
                                <li><strong>Admin:</strong> Can manage clients, devices, billing, and reports</li>
                                <li><strong>Staff:</strong> Limited access to view and basic operations</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('users.show', $user) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update User
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

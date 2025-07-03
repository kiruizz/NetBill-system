@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">User Details</h4>
                    <div>
                        <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>
                <div class="card-body">
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

                    <div class="row">
                        <div class="col-md-8">
                            <h5>User Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Name:</strong></td>
                                    <td>{{ $user->name }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($user->email_verified_at)
                                            <span class="badge bg-success">Verified</span>
                                        @else
                                            <span class="badge bg-warning">Unverified</span>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Created:</strong></td>
                                    <td>{{ $user->created_at->format('d M Y H:i') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Last Updated:</strong></td>
                                    <td>{{ $user->updated_at->format('d M Y H:i') }}</td>
                                </tr>
                                @if($user->last_login_at)
                                <tr>
                                    <td><strong>Last Login:</strong></td>
                                    <td>{{ $user->last_login_at->format('d M Y H:i') }} ({{ $user->last_login_at->diffForHumans() }})</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                                     style="width: 100px; height: 100px; font-size: 3rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                @if($user->id === auth()->id())
                                    <span class="badge bg-info">This is your account</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6">
                            <h5>Assigned Roles</h5>
                            @if($user->roles->count() > 0)
                                <div class="list-group">
                                    @foreach($user->roles as $role)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong>{{ $role->name }}</strong>
                                                <br><small class="text-muted">{{ $role->permissions->count() }} permissions</small>
                                            </div>
                                            <div>
                                                <span class="badge bg-{{ $role->name === 'Super Admin' ? 'danger' : ($role->name === 'Admin' ? 'warning' : 'secondary') }}">
                                                    {{ $role->name }}
                                                </span>
                                                @if($user->id !== auth()->id() || $role->name !== 'Super Admin' || \App\Models\User::role('Super Admin')->count() > 1)
                                                    <form action="{{ route('users.revoke-role', $user) }}" method="POST" class="d-inline ms-2">
                                                        @csrf
                                                        <input type="hidden" name="role" value="{{ $role->name }}">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                                onclick="return confirm('Are you sure you want to revoke this role?')">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <!-- Add Role Form -->
                                <div class="mt-3">
                                    <form action="{{ route('users.assign-role', $user) }}" method="POST" class="d-flex">
                                        @csrf
                                        <select name="role" class="form-select me-2" required>
                                            <option value="">Select Role to Add</option>
                                            @foreach(\Spatie\Permission\Models\Role::all() as $role)
                                                @if(!$user->hasRole($role->name))
                                                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add Role
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No roles assigned to this user.
                                </div>
                            @endif
                        </div>

                        <div class="col-md-6">
                            <h5>Quick Actions</h5>
                            <div class="d-grid gap-2">
                                <!-- Reset Password -->
                                <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#resetPasswordModal">
                                    <i class="fas fa-key"></i> Reset Password
                                </button>

                                <!-- Send Email Verification (if not verified) -->
                                @if(!$user->email_verified_at)
                                    <button type="button" class="btn btn-info">
                                        <i class="fas fa-envelope"></i> Send Verification Email
                                    </button>
                                @endif

                                <!-- Delete User (if not current user) -->
                                @if($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger w-100" 
                                                onclick="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                            <i class="fas fa-trash"></i> Delete User
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

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reset Password for {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('users.reset-password', $user) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="modal_password" class="form-label">New Password</label>
                        <input type="password" name="password" id="modal_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="modal_password_confirmation" class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="modal_password_confirmation" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

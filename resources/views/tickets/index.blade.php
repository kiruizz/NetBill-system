@extends('layouts.app')

@section('title', 'Support Tickets')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Support Tickets</h2>
                <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create Ticket
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

            <!-- Filters -->
            <div class="card mb-3">
                <div class="card-body">
                    <form method="GET" action="{{ route('tickets.index') }}" class="row g-3">
                        <div class="col-md-2">
                            <select name="status" class="form-select">
                                <option value="">All Statuses</option>
                                <option value="open" {{ request('status') === 'open' ? 'selected' : '' }}>Open</option>
                                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                <option value="resolved" {{ request('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                                <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="priority" class="form-select">
                                <option value="">All Priorities</option>
                                <option value="low" {{ request('priority') === 'low' ? 'selected' : '' }}>Low</option>
                                <option value="medium" {{ request('priority') === 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="high" {{ request('priority') === 'high' ? 'selected' : '' }}>High</option>
                                <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="category" class="form-select">
                                <option value="">All Categories</option>
                                <option value="technical" {{ request('category') === 'technical' ? 'selected' : '' }}>Technical</option>
                                <option value="billing" {{ request('category') === 'billing' ? 'selected' : '' }}>Billing</option>
                                <option value="general" {{ request('category') === 'general' ? 'selected' : '' }}>General</option>
                                <option value="complaint" {{ request('category') === 'complaint' ? 'selected' : '' }}>Complaint</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="client_id" class="form-select">
                                <option value="">All Clients</option>
                                @foreach($clients as $client)
                                    <option value="{{ $client->id }}" {{ request('client_id') == $client->id ? 'selected' : '' }}>
                                        {{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="assigned_to" class="form-select">
                                <option value="">All Assignees</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('assigned_to') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Tickets</h5>
                </div>
                <div class="card-body">
                    @if($tickets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Ticket #</th>
                                        <th>Title</th>
                                        <th>Client</th>
                                        <th>Category</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Assigned To</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tickets as $ticket)
                                        <tr>
                                            <td>
                                                <strong>{{ $ticket->ticket_number }}</strong>
                                            </td>
                                            <td>
                                                <strong>{{ $ticket->title }}</strong>
                                                <br><small class="text-muted">{{ Str::limit($ticket->description, 50) }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('clients.show', $ticket->client) }}" class="text-decoration-none">
                                                    {{ $ticket->client->name }}
                                                </a>
                                            </td>
                                            <td>
                                                @php
                                                    $categoryIcons = [
                                                        'technical' => 'fas fa-wrench',
                                                        'billing' => 'fas fa-file-invoice',
                                                        'general' => 'fas fa-question-circle',
                                                        'complaint' => 'fas fa-exclamation-triangle'
                                                    ];
                                                @endphp
                                                <i class="{{ $categoryIcons[$ticket->category] ?? 'fas fa-question-circle' }}"></i>
                                                {{ ucfirst($ticket->category) }}
                                            </td>
                                            <td>
                                                @php
                                                    $priorityColors = [
                                                        'low' => 'success',
                                                        'medium' => 'warning',
                                                        'high' => 'danger',
                                                        'urgent' => 'dark'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $priorityColors[$ticket->priority] ?? 'secondary' }}">
                                                    {{ ucfirst($ticket->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusColors = [
                                                        'open' => 'primary',
                                                        'in_progress' => 'warning',
                                                        'resolved' => 'success',
                                                        'closed' => 'secondary'
                                                    ];
                                                @endphp
                                                <span class="badge bg-{{ $statusColors[$ticket->status] ?? 'secondary' }}">
                                                    {{ ucwords(str_replace('_', ' ', $ticket->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($ticket->assignedTo)
                                                    {{ $ticket->assignedTo->name }}
                                                @else
                                                    <span class="text-muted">Unassigned</span>
                                                @endif
                                            </td>
                                            <td>{{ $ticket->created_at->format('M d, Y') }}</td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tickets.show', $ticket) }}" 
                                                       class="btn btn-sm btn-outline-info" title="View">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('tickets.edit', $ticket) }}" 
                                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form action="{{ route('tickets.destroy', $ticket) }}" 
                                                          method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="btn btn-sm btn-outline-danger" 
                                                                title="Delete"
                                                                onclick="return confirm('Are you sure you want to delete this ticket?')">
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
                            {{ $tickets->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-ticket-alt fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No tickets found</h5>
                            <p class="text-muted">Create support tickets to track client issues and requests.</p>
                            <a href="{{ route('tickets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Ticket
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

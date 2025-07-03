@extends('layouts.app')

@section('title', 'Payment Details')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Payment Details</h4>
                    <div>
                        @if($payment->status !== 'completed')
                            <a href="{{ route('payments.edit', $payment) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        @endif
                        <a href="{{ route('payments.index') }}" class="btn btn-secondary btn-sm">
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
                        <div class="col-md-6">
                            <h5>Payment Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Payment ID:</strong></td>
                                    <td>PAY-{{ str_pad($payment->id, 6, '0', STR_PAD_LEFT) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Amount:</strong></td>
                                    <td>KES {{ number_format($payment->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>
                                        <span class="badge bg-info">
                                            {{ ucwords(str_replace('_', ' ', $payment->payment_method)) }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Date:</strong></td>
                                    <td>{{ $payment->payment_date->format('d M Y') }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status:</strong></td>
                                    <td>
                                        @if($payment->status === 'completed')
                                            <span class="badge bg-success">Completed</span>
                                        @elseif($payment->status === 'pending')
                                            <span class="badge bg-warning">Pending</span>
                                        @else
                                            <span class="badge bg-danger">Failed</span>
                                        @endif
                                    </td>
                                </tr>
                                @if($payment->reference_number)
                                <tr>
                                    <td><strong>Reference Number:</strong></td>
                                    <td>{{ $payment->reference_number }}</td>
                                </tr>
                                @endif
                            </table>
                        </div>

                        <div class="col-md-6">
                            <h5>Invoice Information</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>Invoice:</strong></td>
                                    <td>
                                        <a href="{{ route('invoices.show', $payment->invoice) }}">
                                            INV-{{ str_pad($payment->invoice->id, 6, '0', STR_PAD_LEFT) }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Client:</strong></td>
                                    <td>
                                        <a href="{{ route('clients.show', $payment->invoice->client) }}">
                                            {{ $payment->invoice->client->name }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Invoice Total:</strong></td>
                                    <td>KES {{ number_format($payment->invoice->total_amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Total Paid:</strong></td>
                                    <td>KES {{ number_format($payment->invoice->payments()->sum('amount'), 2) }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Balance:</strong></td>
                                    <td>
                                        @php
                                            $balance = $payment->invoice->total_amount - $payment->invoice->payments()->sum('amount');
                                        @endphp
                                        <strong class="{{ $balance <= 0 ? 'text-success' : 'text-warning' }}">
                                            KES {{ number_format($balance, 2) }}
                                        </strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($payment->notes)
                    <div class="mt-4">
                        <h5>Notes</h5>
                        <div class="alert alert-light">
                            {{ $payment->notes }}
                        </div>
                    </div>
                    @endif

                    <div class="mt-4">
                        <h5>Audit Information</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Created:</strong> {{ $payment->created_at->format('d M Y H:i') }}
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">
                                    <strong>Last Updated:</strong> {{ $payment->updated_at->format('d M Y H:i') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

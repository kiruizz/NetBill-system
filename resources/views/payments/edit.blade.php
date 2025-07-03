@extends('layouts.app')

@section('title', 'Edit Payment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Edit Payment</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('payments.update', $payment) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_id" class="form-label">Invoice <span class="text-danger">*</span></label>
                                    <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror" required>
                                        <option value="">Select Invoice</option>
                                        @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}" 
                                                    data-amount="{{ $invoice->total_amount }}"
                                                    data-paid="{{ $invoice->payments()->sum('amount') }}"
                                                    data-balance="{{ $invoice->total_amount - $invoice->payments()->sum('amount') }}"
                                                    {{ $payment->invoice_id == $invoice->id ? 'selected' : '' }}>
                                                INV-{{ str_pad($invoice->id, 6, '0', STR_PAD_LEFT) }} - 
                                                {{ $invoice->client->name }} - 
                                                KES {{ number_format($invoice->total_amount, 2) }}
                                                (Balance: KES {{ number_format($invoice->total_amount - $invoice->payments()->sum('amount'), 2) }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('invoice_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Payment Amount (KES) <span class="text-danger">*</span></label>
                                    <input type="number" 
                                           step="0.01" 
                                           name="amount" 
                                           id="amount" 
                                           class="form-control @error('amount') is-invalid @enderror" 
                                           value="{{ old('amount', $payment->amount) }}" 
                                           required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div id="balance-info" class="form-text"></div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                        <option value="">Select Payment Method</option>
                                        <option value="cash" {{ old('payment_method', $payment->payment_method) == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method', $payment->payment_method) == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="mobile_money" {{ old('payment_method', $payment->payment_method) == 'mobile_money' ? 'selected' : '' }}>Mobile Money</option>
                                        <option value="cheque" {{ old('payment_method', $payment->payment_method) == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="card" {{ old('payment_method', $payment->payment_method) == 'card' ? 'selected' : '' }}>Card</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" 
                                           name="payment_date" 
                                           id="payment_date" 
                                           class="form-control @error('payment_date') is-invalid @enderror" 
                                           value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" 
                                           required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" 
                                   name="reference_number" 
                                   id="reference_number" 
                                   class="form-control @error('reference_number') is-invalid @enderror" 
                                   value="{{ old('reference_number', $payment->reference_number) }}" 
                                   placeholder="Transaction ID, Check Number, etc.">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea name="notes" 
                                      id="notes" 
                                      rows="3" 
                                      class="form-control @error('notes') is-invalid @enderror" 
                                      placeholder="Additional payment notes...">{{ old('notes', $payment->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('payments.show', $payment) }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Update Payment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const invoiceSelect = document.getElementById('invoice_id');
    const amountInput = document.getElementById('amount');
    const balanceInfo = document.getElementById('balance-info');

    function updateBalanceInfo() {
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        if (selectedOption.value) {
            const balance = parseFloat(selectedOption.dataset.balance);
            balanceInfo.innerHTML = `Outstanding Balance: KES ${balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            amountInput.max = balance;
            amountInput.placeholder = `Max: KES ${balance.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
        } else {
            balanceInfo.innerHTML = '';
            amountInput.max = '';
            amountInput.placeholder = '';
        }
    }

    invoiceSelect.addEventListener('change', updateBalanceInfo);
    
    // Initialize on page load
    updateBalanceInfo();
});
</script>
@endsection

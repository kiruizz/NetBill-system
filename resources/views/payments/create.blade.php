@extends('layouts.app')

@section('title', 'Record Payment')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Record New Payment</h2>
                <a href="{{ route('payments.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Payment Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('payments.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_id" class="form-label">Invoice <span class="text-danger">*</span></label>
                                    <select class="form-select @error('invoice_id') is-invalid @enderror" id="invoice_id" name="invoice_id" required>
                                        <option value="">Select an invoice</option>
                                        @foreach($invoices as $invoice)
                                            <option value="{{ $invoice->id }}" 
                                                    data-amount="{{ $invoice->total_amount }}"
                                                    data-client="{{ $invoice->client->name }}"
                                                    {{ old('invoice_id', $selectedInvoice ? $selectedInvoice->id : '') == $invoice->id ? 'selected' : '' }}>
                                                {{ $invoice->invoice_number }} - {{ $invoice->client->name }} - KES {{ number_format($invoice->total_amount, 2) }}
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
                                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                           id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                                    @error('payment_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Payment Amount (KES) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" min="0.01" required>
                                    <small class="form-text text-muted">Remaining balance: KES <span id="remaining_balance">0.00</span></small>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select class="form-select @error('payment_method') is-invalid @enderror" id="payment_method" name="payment_method" required>
                                        <option value="">Select payment method</option>
                                        <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Cash</option>
                                        <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                        <option value="mobile_money" {{ old('payment_method') == 'mobile_money' ? 'selected' : '' }}>Mobile Money (M-Pesa/Airtel)</option>
                                        <option value="cheque" {{ old('payment_method') == 'cheque' ? 'selected' : '' }}>Cheque</option>
                                        <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Credit/Debit Card</option>
                                    </select>
                                    @error('payment_method')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="reference_number" class="form-label">Reference Number</label>
                            <input type="text" class="form-control @error('reference_number') is-invalid @enderror" 
                                   id="reference_number" name="reference_number" value="{{ old('reference_number') }}"
                                   placeholder="Transaction ID, Receipt Number, etc.">
                            @error('reference_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Invoice Details Card -->
                        <div class="card bg-light mb-3" id="invoice_details" style="display: none;">
                            <div class="card-body">
                                <h6>Selected Invoice Details:</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Client:</strong> <span id="invoice_client"></span><br>
                                        <strong>Invoice Amount:</strong> KES <span id="invoice_amount"></span>
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Already Paid:</strong> KES <span id="already_paid">0.00</span><br>
                                        <strong>Remaining Balance:</strong> KES <span id="balance"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('payments.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Record Payment
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
    // Show invoice details when selected
    document.getElementById('invoice_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const detailsCard = document.getElementById('invoice_details');
        
        if (selectedOption.value) {
            const amount = selectedOption.dataset.amount;
            const client = selectedOption.dataset.client;
            
            document.getElementById('invoice_client').textContent = client;
            document.getElementById('invoice_amount').textContent = parseFloat(amount).toFixed(2);
            document.getElementById('balance').textContent = parseFloat(amount).toFixed(2);
            document.getElementById('remaining_balance').textContent = parseFloat(amount).toFixed(2);
            document.getElementById('amount').value = amount;
            
            detailsCard.style.display = 'block';
        } else {
            detailsCard.style.display = 'none';
        }
    });

    // Trigger change event if invoice is pre-selected
    if (document.getElementById('invoice_id').value) {
        document.getElementById('invoice_id').dispatchEvent(new Event('change'));
    }
</script>
@endpush

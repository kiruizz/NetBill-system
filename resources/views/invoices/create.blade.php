@extends('layouts.app')

@section('title', 'Create Invoice')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Create New Invoice</h2>
                <a href="{{ route('invoices.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Invoices
                </a>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Invoice Details</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('invoices.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="client_id" class="form-label">Client <span class="text-danger">*</span></label>
                                    <select class="form-select @error('client_id') is-invalid @enderror" id="client_id" name="client_id" required>
                                        <option value="">Select a client</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}" {{ old('client_id', request('client_id')) == $client->id ? 'selected' : '' }}>
                                                {{ $client->name }} ({{ $client->email }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('client_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="service_plan_id" class="form-label">Service Plan</label>
                                    <select class="form-select @error('service_plan_id') is-invalid @enderror" id="service_plan_id" name="service_plan_id">
                                        <option value="">Select service plan (optional)</option>
                                        @foreach($servicePlans as $plan)
                                            <option value="{{ $plan->id }}" data-price="{{ $plan->price }}" {{ old('service_plan_id') == $plan->id ? 'selected' : '' }}>
                                                {{ $plan->name }} - KES {{ number_format($plan->price, 2) }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('service_plan_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="invoice_date" class="form-label">Invoice Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                           id="invoice_date" name="invoice_date" value="{{ old('invoice_date', date('Y-m-d')) }}" required>
                                    @error('invoice_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="due_date" class="form-label">Due Date <span class="text-danger">*</span></label>
                                    <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                                           id="due_date" name="due_date" value="{{ old('due_date', date('Y-m-d', strtotime('+30 days'))) }}" required>
                                    @error('due_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="amount" class="form-label">Amount (KES) <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                           id="amount" name="amount" value="{{ old('amount') }}" min="0" required>
                                    @error('amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="tax_amount" class="form-label">Tax Amount (KES)</label>
                                    <input type="number" step="0.01" class="form-control @error('tax_amount') is-invalid @enderror" 
                                           id="tax_amount" name="tax_amount" value="{{ old('tax_amount', 0) }}" min="0">
                                    @error('tax_amount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="discount_amount" class="form-label">Discount Amount (KES)</label>
                                    <input type="number" step="0.01" class="form-control @error('discount_amount') is-invalid @enderror" 
                                           id="discount_amount" name="discount_amount" value="{{ old('discount_amount', 0) }}" min="0">
                                    @error('discount_amount')
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

                        <div class="mb-3">
                            <label for="notes" class="form-label">Notes</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="2">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Total Calculation -->
                        <div class="card bg-light mb-3">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <strong>Total Calculation:</strong>
                                        <p class="mb-0">Amount + Tax - Discount = Total</p>
                                    </div>
                                    <div class="col-md-6 text-end">
                                        <h5 class="mb-0">Total: KES <span id="total_amount">0.00</span></h5>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('invoices.index') }}" class="btn btn-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Invoice
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
    // Auto-populate amount when service plan is selected
    document.getElementById('service_plan_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const price = selectedOption.dataset.price;
        if (price) {
            document.getElementById('amount').value = price;
            calculateTotal();
        }
    });

    // Calculate total amount
    function calculateTotal() {
        const amount = parseFloat(document.getElementById('amount').value) || 0;
        const tax = parseFloat(document.getElementById('tax_amount').value) || 0;
        const discount = parseFloat(document.getElementById('discount_amount').value) || 0;
        
        const total = amount + tax - discount;
        document.getElementById('total_amount').textContent = total.toFixed(2);
    }

    // Add event listeners for calculation
    ['amount', 'tax_amount', 'discount_amount'].forEach(id => {
        document.getElementById(id).addEventListener('input', calculateTotal);
    });

    // Calculate initial total
    calculateTotal();
</script>
@endpush

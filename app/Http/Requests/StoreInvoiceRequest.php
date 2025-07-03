<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-billing');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'client_id' => 'required|exists:clients,id',
            'subscription_id' => 'nullable|exists:subscriptions,id',
            'billing_period_start' => 'required|date',
            'billing_period_end' => 'required|date|after:billing_period_start',
            'due_date' => 'required|date|after_or_equal:today',
            'subtotal' => 'required|numeric|min:0',
            'tax_amount' => 'nullable|numeric|min:0',
            'discount_amount' => 'nullable|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'notes' => 'nullable|string|max:1000',
            'status' => 'required|in:draft,sent,paid,overdue,cancelled',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.total_price' => 'required|numeric|min:0',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'Client is required.',
            'client_id.exists' => 'Selected client does not exist.',
            'billing_period_start.required' => 'Billing period start date is required.',
            'billing_period_end.required' => 'Billing period end date is required.',
            'billing_period_end.after' => 'Billing period end date must be after start date.',
            'due_date.required' => 'Due date is required.',
            'due_date.after_or_equal' => 'Due date cannot be in the past.',
            'subtotal.required' => 'Subtotal is required.',
            'subtotal.numeric' => 'Subtotal must be a valid number.',
            'total_amount.required' => 'Total amount is required.',
            'total_amount.numeric' => 'Total amount must be a valid number.',
            'currency.required' => 'Currency is required.',
            'currency.size' => 'Currency must be a 3-letter code (e.g., KES).',
            'status.required' => 'Invoice status is required.',
            'status.in' => 'Invalid invoice status selected.',
            'items.required' => 'At least one invoice item is required.',
            'items.min' => 'At least one invoice item is required.',
            'items.*.description.required' => 'Item description is required.',
            'items.*.quantity.required' => 'Item quantity is required.',
            'items.*.quantity.min' => 'Item quantity must be at least 1.',
            'items.*.unit_price.required' => 'Item unit price is required.',
            'items.*.unit_price.numeric' => 'Item unit price must be a valid number.',
            'items.*.total_price.required' => 'Item total price is required.',
            'items.*.total_price.numeric' => 'Item total price must be a valid number.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set default currency if not provided
        if (!$this->has('currency')) {
            $this->merge([
                'currency' => 'KES', // Default to Kenyan Shilling
            ]);
        }

        // Calculate total amount based on items if not provided
        if ($this->has('items') && is_array($this->items)) {
            $subtotal = collect($this->items)->sum('total_price');
            $taxAmount = $this->input('tax_amount', 0);
            $discountAmount = $this->input('discount_amount', 0);
            
            $this->merge([
                'subtotal' => $subtotal,
                'total_amount' => $subtotal + $taxAmount - $discountAmount,
            ]);
        }
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-service-plans');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:service_plans,name',
            'description' => 'nullable|string|max:1000',
            'speed_download' => 'required|integer|min:1',
            'speed_upload' => 'required|integer|min:1',
            'data_limit' => 'nullable|integer|min:0',
            'price' => 'required|numeric|min:0',
            'setup_fee' => 'nullable|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,quarterly,semi-annually,annually',
            'type' => 'required|in:residential,business,corporate',
            'features' => 'nullable|array',
            'features.*' => 'string|max:255',
            'is_unlimited' => 'boolean',
            'fair_usage_policy' => 'nullable|string|max:1000',
            'status' => 'required|in:active,inactive',
            'priority_support' => 'boolean',
            'dedicated_ip' => 'boolean',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Service plan name is required.',
            'name.unique' => 'A service plan with this name already exists.',
            'speed_download.required' => 'Download speed is required.',
            'speed_download.integer' => 'Download speed must be a valid number.',
            'speed_download.min' => 'Download speed must be at least 1 Mbps.',
            'speed_upload.required' => 'Upload speed is required.',
            'speed_upload.integer' => 'Upload speed must be a valid number.',
            'speed_upload.min' => 'Upload speed must be at least 1 Mbps.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price cannot be negative.',
            'billing_cycle.required' => 'Billing cycle is required.',
            'billing_cycle.in' => 'Invalid billing cycle selected.',
            'type.required' => 'Service plan type is required.',
            'type.in' => 'Invalid service plan type selected.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_unlimited' => $this->boolean('is_unlimited'),
            'priority_support' => $this->boolean('priority_support'),
            'dedicated_ip' => $this->boolean('dedicated_ip'),
        ]);
    }
}

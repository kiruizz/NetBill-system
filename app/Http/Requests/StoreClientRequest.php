<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('manage-clients');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'id_number' => 'nullable|string|max:20|unique:clients,id_number',
            'connection_type' => 'required|in:residential,business,corporate',
            'status' => 'required|in:active,inactive,suspended',
            'service_plan_id' => 'required|exists:service_plans,id',
            'installation_address' => 'nullable|string|max:500',
            'billing_address' => 'nullable|string|max:500',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'notes' => 'nullable|string|max:1000',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Client name is required.',
            'email.required' => 'Email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required.',
            'service_plan_id.required' => 'Please select a service plan.',
            'service_plan_id.exists' => 'Selected service plan does not exist.',
            'connection_type.required' => 'Connection type is required.',
            'connection_type.in' => 'Invalid connection type selected.',
            'status.required' => 'Client status is required.',
            'status.in' => 'Invalid status selected.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerPhoneUuidRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customerid' => 'required|uuid',
            'phoneId' => 'required|uuid'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customerid' => $this->route('customerid'),
            'phoneId' => $this->route('phoneId')
        ]);
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'customerid.required' => 'Customer ID is required.',
            'customerid.uuid' => 'Invalid customer ID format.',
            'phoneId.required' => 'Phone ID is required.',
            'phoneId.uuid' => 'Invalid phone ID format.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerAddressUuidRequest extends FormRequest
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
            'addressId' => 'required|uuid'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customerid' => $this->route('customerid'),
            'addressId' => $this->route('addressId')
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
            'addressId.required' => 'Address ID is required.',
            'addressId.uuid' => 'Invalid address ID format.',
        ];
    }
}

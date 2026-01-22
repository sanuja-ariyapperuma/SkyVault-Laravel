<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerEmailUuidRequest extends FormRequest
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
            'emailId' => 'required|uuid'
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'customerid' => $this->route('customerid'),
            'emailId' => $this->route('emailId')
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
            'emailId.required' => 'Email ID is required.',
            'emailId.uuid' => 'Invalid email ID format.',
        ];
    }
}

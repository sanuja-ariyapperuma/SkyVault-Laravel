<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePhoneRequest extends FormRequest
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
            'phone_number' => [
                'required',
                'string',
                'regex:/^[0-9]{10}$/',
                Rule::unique('customer_phones', 'phone_number')
                    ->where('customer_id', $this->route('customerId'))
            ],
            'is_default' => 'sometimes|boolean',
            'is_whatsapp' => 'sometimes|boolean',
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number must be exactly 10 digits (numbers only).',
            'phone_number.unique' => 'This phone number already exists for this customer.',
            'is_default.boolean' => 'Default status must be true or false.',
            'is_whatsapp.boolean' => 'WhatsApp status must be true or false.',
        ];
    }
}

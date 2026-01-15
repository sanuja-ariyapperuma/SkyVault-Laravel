<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAddressRequest extends FormRequest
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
            'address_line_1' => [
                'required',
                'string',
                'max:255',
            ],
            'address_line_2' => [
                'nullable',
                'string',
                'max:255',
            ],
            'city' => [
                'required',
                'string',
                'max:100',
            ],
            'state' => [
                'nullable',
                'string',
                'max:100',
            ],
            'postal_code' => [
                'nullable',
                'string',
                'max:20',
            ],
            'country_id' => [
                'required',
                'uuid',
                'exists:countries,id',
            ],
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
            'address_line_1.required' => 'Address line 1 is required.',
            'address_line_1.max' => 'Address line 1 must not exceed 255 characters.',
            'address_line_2.max' => 'Address line 2 must not exceed 255 characters.',
            'city.required' => 'City is required.',
            'city.max' => 'City must not exceed 100 characters.',
            'state.max' => 'State/Province must not exceed 100 characters.',
            'postal_code.max' => 'Postal code must not exceed 20 characters.',
            'country_id.required' => 'Country is required.',
            'country_id.uuid' => 'Please select a valid country.',
            'country_id.exists' => 'Selected country is invalid.',
        ];
    }
}

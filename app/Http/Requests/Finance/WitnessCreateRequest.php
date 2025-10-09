<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WitnessCreateRequest extends FormRequest
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
     */
    public function rules(): array
    {
        return [
            'loan_id' => ['required', 'string', 'exists:finance_loan_applications,id'],
            'name' => ['required', 'string'],
            'mobile' => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'dob' => ['required', 'date', 'before:today'],
            'aadhaar_number' => ['required', 'digits:12'],
            'guardian_name' => ['required', 'string'],
            'address_line_1' => ['required', 'string'],
            'address_line_2' => ['nullable', 'string'],
            'address_city' => ['required', 'string'],
            'address_state' => ['required', 'string'],
            'address_pincode' => ['required', 'digits:6'],
        ];
    }
}

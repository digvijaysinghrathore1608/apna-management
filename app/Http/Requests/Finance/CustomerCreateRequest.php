<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'aadhaar_number' => [
                'required',
                'digits:12', // exactly 12 digits
                function ($attribute, $value, $fail) {
                    $first8 = extractNumberPart($value, firstCount: 8);
                    $exists = Customer::withTrashed()->where('customer_id', $first8)->exists();
                    if ($exists) {
                        $fail("The first 8 digits of Aadhaar already exist in another customer ID.");
                    }
                },
            ],
            'pan_number' => [
                'required',
                'string',
                'regex:/^[A-Z]{5}[0-9]{4}[A-Z]{1}$/',
                'size:10',
            ],
            'f_name' => ['required', 'string'],
            'm_name' => ['nullable', 'string'],
            'l_name' => ['required', 'string'],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/', 'unique:finance_customers,mobile'],
            'email' => ['nullable', 'email', 'unique:finance_customers,email'],
            'DOB' => [
                'required',
                'date',
                'before_or_equal:' . min_dob(),
            ],
            'gender' => [
                'required',
                Rule::in(array_keys(genders())),
            ],
            'branch_id' => ['required', 'exists:finance_branches,id'],
            'father_name' => ['required', 'string'],
            'mother_name' => ['required', 'string'],
            'in_law_father_name' => ['required', 'string'],
            'in_law_mother_name' => ['required', 'string'],
        ];
    }
}

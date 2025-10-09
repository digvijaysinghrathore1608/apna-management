<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\CustomerFamilyMember;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FamilyMemberCreateRequest extends FormRequest
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
         $rules =  [
            'customer_id'      => ['required', 'string', 'exists:finance_customers,id'],
            'name'             => ['required', 'string', 'max:255'],
            'dob'              => ['required', 'date', 'before:today'],
            'mobile'           => ['required', 'string', 'regex:/^[6-9]\d{9}$/'],
            'relation'         => ['required', 'string', 'max:100'],
            'work_type'        => ['required', Rule::in(array_keys(work_type()))],
            'work_detail'      => ['required', 'string', 'max:100'],
            'monthly_income'   => ['required', 'numeric', 'min:0'],
            'address_line_1'   => ['required', 'string', 'max:255'],
            'address_line_2'   => ['nullable', 'string', 'max:255'],
            'address_city'     => ['required', 'string', 'max:100'],
            'address_state'    => ['required', 'string', 'max:100'],
            'address_pincode'  => ['required', 'digits:6'],
            'is_nominee'       => ['nullable', 'boolean'],
        ];

        if ($this->boolean('is_nominee')) {
            $rules['is_nominee'][] = function ($attribute, $value, $fail) {
                $exists = CustomerFamilyMember::where('customer_id', $this->customer_id)
                    ->where('is_nominee', true)
                    ->exists();

                if ($exists) {
                    $fail('This customer already has a nominee assigned.');
                }
            };
        }

        return $rules;
    }
}

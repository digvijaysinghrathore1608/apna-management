<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LoanApplicationUpdateStep1Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'group_id' => ['required', 'exists:finance_loan_group_members,id'],
            'branch_id' => ['required', 'exists:finance_branches,id'],
            'loan_schema' => ['required', 'exists:finance_loan_schema,id'],
            'account_holder_name' => ['required', 'string'],
            'account_number' => ['required', 'string'],
            'ifsc_code' => ['required', 'string'],
            'bank_name' => ['required', 'string'],
            'branch_name' => ['required', 'string'],
            'account_type' => ['required', Rule::in(array_keys(ACCOUNT_TYPE))],

            'c_a_line_1' => ['required', 'string'],
            'c_a_line_2' => ['nullable', 'string'],
            'c_a_pincode' => ['required', 'string', 'max:6', 'min:6'],
            'c_a_city' => ['required', 'string'],
            'c_a_state' => ['required', 'string'],

            'in_law_a_line_1' => ['required', 'string'],
            'in_law_a_line_2' => ['nullable', 'string'],
            'in_law_a_pincode' => ['required', 'string', 'max:6', 'min:6'],
            'in_law_a_city' => ['required', 'string'],
            'in_law_a_state' => ['required', 'string'],

            'p_a_line_1' => ['required', 'string'],
            'p_a_line_2' => ['nullable', 'string'],
            'p_a_pincode' => ['required', 'string', 'max:6', 'min:6'],
            'p_a_city' => ['required', 'string'],
            'p_a_state' => ['required', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SchemaCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'unique:finance_loan_schema,name'],
            'amount' => ['required', 'numeric', 'gt:0'],
            'interest_rate' => ['required', 'numeric', 'gt:0'],
            'insurance_amount' => ['required', 'numeric', 'gt:0'],
            'duration_type' => ['required', Rule::in(array_keys(duration_type()))],
            'duration' => ['required', 'numeric', 'gt:0'],
            'late_fee' => ['required', 'numeric'],
            'late_fee_apply' => ['required', Rule::in(days())],
            'emi_amount' => ['required', 'numeric', 'gt:0'],
        ];
    }
}

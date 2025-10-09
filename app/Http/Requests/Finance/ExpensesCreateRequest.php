<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpensesCreateRequest extends FormRequest
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
            'loan_id'      => ['required', 'string', 'exists:finance_loan_applications,id'],
            'expense_type'             => ['required', 'string', 'max:255'],
            'amount'   => ['required', 'numeric', 'min:0'],
            'remarks'   => ['nullable', 'string', 'max:255'],
        ];
    }
}

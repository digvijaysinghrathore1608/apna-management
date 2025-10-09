<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GroupCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'branch_id' => ['required', 'exists:finance_branches,id'],
            'customer_id' => ['required', 'exists:finance_customers,id'],
            'area' => ['required', 'string'],
        ];
    }
}

<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchCreateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', 'unique:finance_branches,code'],
            'name' => ['required', 'string', 'unique:finance_branches,name'],
            'email' => ['required', 'email', 'unique:finance_branches,email'],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/', 'unique:finance_branches,mobile'],
            'address' => ['required', 'string'],
        ];
    }
}

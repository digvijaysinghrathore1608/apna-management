<?php

namespace App\Http\Requests\Finance;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BranchUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => ['required', 'string', Rule::unique('finance_branches', 'code')->ignore($this->route('branch'))],
            'name' => ['required', 'string', Rule::unique('finance_branches', 'name')->ignore($this->route('branch'))],
            'email' => ['required', 'email', Rule::unique('finance_branches', 'email')->ignore($this->route('branch'))],
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/', Rule::unique('finance_branches', 'mobile')->ignore($this->route('branch'))],
            'address' => ['required', 'string'],
        ];
    }
}

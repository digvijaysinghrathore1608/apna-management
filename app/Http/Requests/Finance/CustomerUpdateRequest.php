<?php

namespace App\Http\Requests\Finance;

use App\Models\Finance\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CustomerUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'f_name' => ['required', 'string'],
            'm_name' => ['nullable', 'string'],
            'l_name' => ['required', 'string'],
            'mobile' => [
                'required',
                'regex:/^[6-9]\d{9}$/',
                Rule::unique('finance_customers', 'mobile')->ignore($this->route('customer'))
            ],
            'email' => [
                'nullable',
                'email',
                Rule::unique('finance_customers', 'email')->ignore($this->route('customer'))
            ],
            'DOB' => [
                'required',
                'date',
                'before_or_equal:' . now()->subYears(MINIMUM_AGE)->format('Y-m-d'),
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

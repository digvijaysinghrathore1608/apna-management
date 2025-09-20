<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
            return [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'unique:users,email'],
                'mobile' => ['required', 'string', 'max:15', 'unique:users,mobile'],
                'password' => ['required', 'string', 'min:8', 'confirmed'],
                'terms_accepted' => ['accepted'],
            ];
    }
}

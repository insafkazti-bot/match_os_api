<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'unique:admins,email'],
            'password'   => ['required', 'string', 'min:8'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Un administrateur avec cet email existe déjà.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
        ];
    }
}

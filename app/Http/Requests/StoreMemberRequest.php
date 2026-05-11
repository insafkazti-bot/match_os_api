<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMemberRequest extends FormRequest
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
            'email'      => ['required', 'email', 'unique:members,email'],
            'password'   => ['required', 'string', 'min:8'],
            'phone'      => ['nullable', 'string', 'max:20'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
            'position'   => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'email.unique' => 'Un membre avec cet email existe déjà.',
            'email.email'  => 'L\'adresse email n\'est pas valide.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}

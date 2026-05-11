<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('id');

        return [
            'first_name' => ['sometimes', 'required', 'string', 'max:255'],
            'last_name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'required', 'email', 'unique:admins,email,' . $adminId],
            'password' => ['sometimes', 'nullable', 'string', 'min:8'],
            'avatar_url' => ['nullable', 'url', 'max:500'],
        ];
    }
}


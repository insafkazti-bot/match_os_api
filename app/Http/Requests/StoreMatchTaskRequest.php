<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $rules = [
            'id_match'  => ['required', 'integer', 'exists:matches,id'],
            'id_task'   => ['required', 'integer', 'exists:tasks,id'],
            'status'    => ['nullable', 'in:a_faire,en_cours,termine'],
            'notes'     => ['nullable', 'string'],
            'deadline'  => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];

        $user = $this->user();
        if ($user && $user instanceof \App\Models\Admin) {
            $rules['id_member'] = ['required', 'integer', 'exists:members,id'];
        }

        return $rules;
    }
}

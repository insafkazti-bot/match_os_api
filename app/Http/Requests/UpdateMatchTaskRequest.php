<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMatchTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_match'  => ['sometimes', 'required', 'integer', 'exists:matches,id'],
            'id_task'   => ['sometimes', 'required', 'integer', 'exists:tasks,id'],
            'status'    => ['nullable', 'in:a_faire,en_cours,termine'],
            'notes'     => ['nullable', 'string'],
            'deadline'  => ['nullable', 'date_format:Y-m-d H:i:s'],
        ];
    }
}

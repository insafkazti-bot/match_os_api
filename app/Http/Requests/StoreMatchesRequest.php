<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMatchesRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'team_a_name' => ['required', 'string', 'max:255'],
            'team_b_name' => ['required', 'string', 'max:255'],
            'match_date'  => ['required', 'date_format:Y-m-d\TH:i'],
            'location'    => ['nullable', 'string', 'max:255'],
            'status'      => ['nullable', 'in:planifie,en_cours,termine'],
            'score_a'     => ['nullable', 'integer', 'min:0'],
            'score_b'     => ['nullable', 'integer', 'min:0'],
        ];
    }
}

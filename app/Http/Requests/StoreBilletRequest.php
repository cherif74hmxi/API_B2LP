<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBilletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'BIL_DATE' => ['required', 'date'],
            'BIL_TITRE' => ['required', 'string', 'max:255'],
            'BIL_CONTENU' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'BIL_DATE' => $this->input('BIL_DATE', $this->input('date', now()->toDateString())),
            'BIL_TITRE' => $this->input('BIL_TITRE', $this->input('titre', $this->input('title'))),
            'BIL_CONTENU' => $this->input('BIL_CONTENU', $this->input('contenu', $this->input('body'))),
        ]);
    }
}

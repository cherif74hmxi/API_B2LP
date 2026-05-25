<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBilletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return (bool) $this->user()?->isAdmin();
    }

    public function rules(): array
    {
        return [
            'BIL_DATE' => ['sometimes', 'required', 'date'],
            'BIL_TITRE' => ['sometimes', 'required', 'string', 'max:255'],
            'BIL_CONTENU' => ['sometimes', 'required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $mapped = [];

        if ($this->has('date') && !$this->has('BIL_DATE')) {
            $mapped['BIL_DATE'] = $this->input('date');
        }

        if (($this->has('titre') || $this->has('title')) && !$this->has('BIL_TITRE')) {
            $mapped['BIL_TITRE'] = $this->input('titre', $this->input('title'));
        }

        if (($this->has('contenu') || $this->has('body')) && !$this->has('BIL_CONTENU')) {
            $mapped['BIL_CONTENU'] = $this->input('contenu', $this->input('body'));
        }

        if ($mapped !== []) {
            $this->merge($mapped);
        }
    }
}

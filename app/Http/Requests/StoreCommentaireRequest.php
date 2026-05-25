<?php

namespace App\Http\Requests;

use App\Models\Billet;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCommentaireRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'COM_DATE' => ['required', 'date'],
            'COM_CONTENU' => ['required', 'string'],
            'billet_id' => [
                Rule::requiredIf(!$this->route('billet')),
                'integer',
                Rule::exists('billets', 'id'),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        $routeBillet = $this->route('billet');

        $this->merge([
            'COM_DATE' => $this->input('COM_DATE', $this->input('date', now()->toDateString())),
            'COM_CONTENU' => $this->input('COM_CONTENU', $this->input('contenu', $this->input('commentaire'))),
            'billet_id' => $routeBillet instanceof Billet
                ? $routeBillet->getKey()
                : $this->input('billet_id'),
        ]);
    }
}

<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BilletsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->getKey(),
            'Date' => $this->BIL_DATE?->toDateString(),
            'Titre' => $this->BIL_TITRE,
            'Contenu' => $this->BIL_CONTENU,
            'Auteur' => $this->whenLoaded('user', fn () => $this->user?->name),
        ];
    }
}

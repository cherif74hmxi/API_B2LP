<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BilletResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return [
            'Date' => $this->BIL_DATE,
            'Titre' => $this->BIL_TITRE,
            'Contenu' => $this->BIL_CONTENU,
            'Commentaires' => CommentaireResource::collection($this->commentaires),
        ];
    }
}

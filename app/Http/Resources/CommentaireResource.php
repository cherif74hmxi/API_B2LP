<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentaireResource extends JsonResource
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
            'Date' => $this->COM_DATE?->toDateString(),
            'Auteur' => $this->whenLoaded('user', fn () => $this->user?->name),
            'Utilisateur' => $this->whenLoaded('user', fn () => new UserResource($this->user)),
            'BilletId' => $this->billet_id,
            'Contenu' => $this->COM_CONTENU,
        ];
    }
}

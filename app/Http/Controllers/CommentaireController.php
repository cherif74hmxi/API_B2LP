<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentaireRequest;
use App\Http\Resources\CommentaireResource;
use App\Models\Billet;
use App\Models\Commentaire;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class CommentaireController extends Controller
{
    use ApiResponse;

    public function index(Billet $billet): JsonResponse
    {
        try {
            $commentaires = $billet->commentaires()
                ->with('user.role')
                ->orderBy('COM_DATE')
                ->orderBy('id')
                ->get();

            return $this->successResponse(
                CommentaireResource::collection($commentaires),
                'Liste des commentaires.'
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function store(StoreCommentaireRequest $request, ?Billet $billet = null): JsonResponse
    {
        try {
            $data = $request->validated();

            $commentaire = Commentaire::create([
                'COM_DATE' => $data['COM_DATE'],
                'COM_CONTENU' => $data['COM_CONTENU'],
                'billet_id' => $billet?->getKey() ?? $data['billet_id'],
                'user_id' => $request->user()->getKey(),
            ]);

            $commentaire->load('user.role');

            return $this->successResponse(
                new CommentaireResource($commentaire),
                'Commentaire ajoute avec succes.',
                201
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function destroy(Commentaire $commentaire): JsonResponse
    {
        try {
            $commentaire->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }
}

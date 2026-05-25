<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBilletRequest;
use App\Http\Requests\UpdateBilletRequest;
use App\Http\Resources\BilletResource;
use App\Http\Resources\BilletsResource;
use App\Models\Billet;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class BilletController extends Controller
{
    use ApiResponse;

    public function index(): JsonResponse
    {
        try {
            $billets = Billet::with('user.role')
                ->orderByDesc('BIL_DATE')
                ->orderByDesc('id')
                ->get();

            return $this->successResponse(
                BilletsResource::collection($billets),
                'Liste des billets.'
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function store(StoreBilletRequest $request): JsonResponse
    {
        try {
            $billet = Billet::create([
                ...$request->validated(),
                'user_id' => $request->user()->getKey(),
            ]);

            $billet->load('user.role');

            return $this->successResponse(
                new BilletResource($billet),
                'Billet cree avec succes.',
                201
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function show(Billet $billet): JsonResponse
    {
        try {
            $billet->load(['user.role', 'commentaires.user.role']);

            return $this->successResponse(
                new BilletResource($billet),
                'Detail du billet.'
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function update(UpdateBilletRequest $request, Billet $billet): JsonResponse
    {
        try {
            $billet->update($request->validated());
            $billet->load(['user.role', 'commentaires.user.role']);

            return $this->successResponse(
                new BilletResource($billet),
                'Billet modifie avec succes.'
            );
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function destroy(Billet $billet): JsonResponse
    {
        try {
            $billet->delete();

            return response()->json(null, 204);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }
}

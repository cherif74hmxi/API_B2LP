<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBilletRequest;
use App\Http\Requests\UpdateBilletRequest;
use App\Http\Resources\{BilletResource, BilletsResource};
use App\Models\Billet;
use Illuminate\Support\Facades\Log;

class BilletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            //Le résultat de la requête est retourné directement en JSON
            //return Billet::all();
            return BilletsResource::collection(Billet::all());
        }
        catch(\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur accès base de données');
            return response()->json([
                'message' => 'Ressource indisponible.'], 500);
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBilletRequest $request)
    {
        $billet = Billet::create($request->validated());

        return (new BilletsResource($billet))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        //
        try {
            $billetResource = new BilletResource(Billet::with('commentaires','commentaires.user')->findOrFail($id));
            return $billetResource;
        }
        catch(\Illuminate\Database\QueryException $e) {
            Log::error('Erreur accès base de données');
            return response()->json([
                'message' => 'Ressource indisponible.'], 500);
        }
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Billet $billet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBilletRequest $request, Billet $billet)
    {
        $billet->update($request->validated());

        return new BilletsResource($billet);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Billet $billet)
    {
        $this->authorize('delete', $billet);

        $billet->delete();

        return response()->json(null, 204);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBilletRequest;
use App\Http\Requests\UpdateBilletRequest;
use App\Models\Billet;
use App\Models\Commentaire;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class BlogAdminController extends Controller
{
    /**
     * Display dashboard for blog administration.
     */
    public function index(): View
    {
        $billets = Billet::with(['commentaires.user'])
            ->orderByDesc('BIL_DATE')
            ->orderByDesc('id')
            ->get();

        return view('admin.dashboard', compact('billets'));
    }

    /**
     * Show billet edit form.
     */
    public function editBillet(Billet $billet): View
    {
        return view('admin.edit-billet', compact('billet'));
    }

    /**
     * Store a new billet.
     */
    public function storeBillet(StoreBilletRequest $request): RedirectResponse
    {
        try {
            Billet::create([
                ...$request->validated(),
                'user_id' => $request->user()->getKey(),
            ]);

            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Billet cree avec succes.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees');

            return back()->withErrors([
                'billet' => 'Impossible de creer le billet.',
            ])->withInput();
        }
    }

    /**
     * Update an existing billet.
     */
    public function updateBillet(UpdateBilletRequest $request, Billet $billet): RedirectResponse
    {
        try {
            $billet->update($request->validated());

            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Billet modifie avec succes.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees');

            return back()->withErrors([
                'billet' => 'Impossible de modifier le billet.',
            ])->withInput();
        }
    }

    /**
     * Delete a billet.
     */
    public function destroyBillet(Billet $billet): RedirectResponse
    {
        try {
            $billet->delete();

            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Billet supprime avec succes.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees');

            return back()->withErrors([
                'billet' => 'Impossible de supprimer le billet.',
            ]);
        }
    }

    /**
     * Delete a commentaire.
     */
    public function destroyCommentaire(Commentaire $commentaire): RedirectResponse
    {
        try {
            $commentaire->delete();

            return redirect()
                ->route('admin.dashboard')
                ->with('status', 'Commentaire supprime avec succes.');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees');

            return back()->withErrors([
                'commentaire' => 'Impossible de supprimer le commentaire.',
            ]);
        }
    }
}

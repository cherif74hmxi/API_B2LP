<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use App\Support\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    use ApiResponse;

    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        try {
            $role = Role::where('slug', Role::SWIMMER)->first();

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role_id' => $role?->id,
                'is_admin' => false,
            ]);

            $token = $user->createToken('auth_token')->plainTextToken;

            return $this->successResponse([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => new UserResource($user->load('role')),
            ], 'Compte cree avec succes.', 201);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::channel('projectLog')->error('Erreur acces base de donnees', [
                'exception' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ressource indisponible.', 500);
        }
    }

    public function show(Request $request): JsonResponse
    {
        return $this->successResponse(
            new UserResource($request->user()->load('role')),
            'Utilisateur connecte.'
        );
    }
}

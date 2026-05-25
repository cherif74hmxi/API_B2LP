<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\BilletController;
use App\Http\Controllers\CommentaireController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'store']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/billets', [BilletController::class, 'index']);
Route::get('/billets/{billet}', [BilletController::class, 'show'])->whereNumber('billet');
Route::get('/billets/{billet}/commentaires', [CommentaireController::class, 'index'])->whereNumber('billet');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [UserController::class, 'show']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/user/logout', [AuthController::class, 'logout']);

    Route::post('/commentaires', [CommentaireController::class, 'store']);
    Route::post('/billets/{billet}/commentaires', [CommentaireController::class, 'store'])->whereNumber('billet');

    Route::middleware('admin')->group(function () {
        Route::post('/billets', [BilletController::class, 'store']);
        Route::put('/billets/{billet}', [BilletController::class, 'update'])->whereNumber('billet');
        Route::patch('/billets/{billet}', [BilletController::class, 'update'])->whereNumber('billet');
        Route::delete('/billets/{billet}', [BilletController::class, 'destroy'])->whereNumber('billet');
        Route::delete('/commentaires/{commentaire}', [CommentaireController::class, 'destroy'])->whereNumber('commentaire');
    });
});

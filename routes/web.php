<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BlogAdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/', [BlogAdminController::class, 'index'])->name('dashboard');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
        Route::post('/billets', [BlogAdminController::class, 'storeBillet'])->name('billets.store');
        Route::get('/billets/{billet}/edit', [BlogAdminController::class, 'editBillet'])->name('billets.edit');
        Route::put('/billets/{billet}', [BlogAdminController::class, 'updateBillet'])->name('billets.update');
        Route::delete('/billets/{billet}', [BlogAdminController::class, 'destroyBillet'])->name('billets.destroy');
        Route::delete('/commentaires/{commentaire}', [BlogAdminController::class, 'destroyCommentaire'])->name('commentaires.destroy');
    });
});

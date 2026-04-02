<?php

use App\Http\Controllers\auth\RegisteredUserController;
use App\Http\Controllers\auth\SessionController;
use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ideas');

Route::middleware('auth')->group(function () {
    Route::get('/ideas', [IdeaController::class, 'index'])->name('idea.index');
    Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show');

    Route::post('/logout', [SessionController::class, 'destroy']);
});

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

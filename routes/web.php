<?php

use App\Http\Controllers\auth\RegisteredUserController;
use App\Http\Controllers\auth\SessionController;
use App\Http\Controllers\IdeaController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/ideas');

Route::middleware('auth')->group(function (): void {
    Route::get('/ideas', [IdeaController::class, 'index'])->name('idea.index');
    Route::get('/ideas/create', [IdeaController::class, 'create'])->name('idea.create');
    Route::post('/ideas', [IdeaController::class, 'store'])->name('idea.store');
    Route::get('/ideas/{idea}', [IdeaController::class, 'show'])->name('idea.show');
    Route::delete('/ideas/{idea}', [IdeaController::class, 'destroy'])->name('idea.destroy');
    Route::get('/ideas/{idea}/edit', [IdeaController::class, 'edit'])->name('idea.edit');
    Route::post('/ideas/{idea}/edit', [IdeaController::class, 'update'])->name('idea.update');

    Route::post('/logout', [SessionController::class, 'destroy']);
});

Route::middleware('guest')->group(function (): void {
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);

    Route::get('/login', [SessionController::class, 'create'])->name('login');
    Route::post('/login', [SessionController::class, 'store']);
});

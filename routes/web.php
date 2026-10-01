<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LspController;

Route::get('/', function () {
    return redirect()->route('lsp.dashboard');
});

// Alias default auth route
Route::get('/login', [LspController::class, 'showLogin'])->name('login');

Route::prefix('lsp')->name('lsp.')->group(function () {
    Route::get('/login', [LspController::class, 'showLogin'])->name('login');
    Route::post('/login', [LspController::class, 'login'])->name('login.post');
    Route::post('/logout', [LspController::class, 'logout'])->name('logout');

    Route::middleware('auth')->group(function () {
        Route::get('/dashboard', [LspController::class, 'dashboard'])->name('dashboard');

        // Schemes
        Route::get('/schemes', [LspController::class, 'schemes'])->name('schemes');
        Route::post('/schemes', [LspController::class, 'storeScheme'])->name('schemes.store');
        Route::put('/schemes/{scheme}', [LspController::class, 'updateScheme'])->name('schemes.update');
        Route::delete('/schemes/{scheme}', [LspController::class, 'destroyScheme'])->name('schemes.destroy');

        // Participants
        Route::get('/participants', [LspController::class, 'participants'])->name('participants');
        Route::post('/participants', [LspController::class, 'storeParticipant'])->name('participants.store');
        Route::put('/participants/{participant}', [LspController::class, 'updateParticipant'])->name('participants.update');
        Route::delete('/participants/{participant}', [LspController::class, 'destroyParticipant'])->name('participants.destroy');
    });
});

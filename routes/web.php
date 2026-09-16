<?php

use App\Http\Controllers\AssistantController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return redirect()->route('login');
})->name('home');

Route::middleware(['auth'])->group(function (): void {
    // Dashboard (Role-specific logic handled in controller)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Assistant Chatbot
    Route::post('/assistant/chat', [AssistantController::class, 'chat'])->name('assistant.chat');

    // Tickets
    Route::resource('tickets', TicketController::class);
    Route::post('/tickets/{ticket}/assign', [TicketController::class, 'assign'])
        ->name('tickets.assign')
        ->middleware('role:admin');

    // Ticket Comments
    Route::post('/tickets/{ticket}/comments', [CommentController::class, 'store'])
        ->name('tickets.comments.store');

    // Admin-Only Routes
    Route::middleware(['role:admin'])->group(function (): void {
        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('users', UserController::class)->only(['index', 'edit', 'update', 'destroy']);
    });

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

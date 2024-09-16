<?php

use App\Http\Controllers\AgentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RedirectController;
use App\Http\Controllers\SupervisorController;
use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth'])->group(function () {
    // Routes pour les agents
    Route::middleware(['auth', CheckRole::class . ':agent'])->group(function () {
        Route::get('/agent/dashboard', [AgentController::class, 'dashboard'])->name('agent.dashboard');
        Route::get('/agent/calls', [AgentController::class, 'calls'])->name('agent.calls');
        Route::get('/agent/calls/create', [AgentController::class, 'createCall'])->name('agent.calls.create');
        Route::post('/agent/calls', [AgentController::class, 'storeCall'])->name('agent.calls.store');
        
        Route::get('/agent/tickets', [AgentController::class, 'tickets'])->name('agent.tickets');
        Route::get('/agent/tickets/create', [AgentController::class, 'createTicket'])->name('agent.tickets.create');

        Route::get('/agent/tickets/{ticket}', [AgentController::class, 'showTicket'])->name('agent.tickets.show');
        Route::put('/agent/tickets/{ticket}', [AgentController::class, 'updateTicket'])->name('agent.tickets.update');
        Route::post('/agent/tickets/{ticket}/comments', [AgentController::class, 'addComment'])->name('agent.tickets.comment');
        
        Route::post('/agent/tickets',
            [AgentController::class, 'storeTicket']
        )->name('agent.tickets.store');
        Route::put('/agent/tickets/{ticket}', [AgentController::class, 'updateTicket'])->name('agent.tickets.update');
    });

    // Routes pour les superviseurs
    Route::middleware([CheckRole::class . ':supervisor'])->group(function () {
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'dashboard'])->name('supervisor.dashboard');
        Route::get('/supervisor/calls', [SupervisorController::class, 'calls'])->name('supervisor.calls');
        Route::get('/supervisor/tickets', [SupervisorController::class, 'tickets'])->name('supervisor.tickets');
        Route::get('/supervisor/reports', [SupervisorController::class, 'reports'])->name('supervisor.reports');
        Route::get('/supervisor/tickets/{ticket}', [SupervisorController::class, 'showTicket'])->name('supervisor.tickets.show');
        Route::put('/supervisor/tickets/{ticket}', [SupervisorController::class, 'updateTicket'])->name('supervisor.tickets.update');
        Route::post('/supervisor/tickets/{ticket}/comments', [SupervisorController::class, 'addComment'])->name('supervisor.tickets.comment');

        Route::get('/supervisor/notifications', [SupervisorController::class, 'notifications'])->name('supervisor.notifications');
        Route::post('/supervisor/notifications/{id}/read', [SupervisorController::class, 'markNotificationAsRead'])->name('supervisor.notifications.read');
    });
});




require __DIR__.'/auth.php';

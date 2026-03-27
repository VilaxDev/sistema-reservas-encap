<?php

use App\Http\Controllers\AuditorioController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', function () {
    $totalUsuarios = \App\Models\User::count();
    $totalAuditorios = \App\Models\Auditorio::where('activo', true)->count();
    $totalReservas = \App\Models\Reserva::activas()->count();
    $reservasHoy = \App\Models\Reserva::activas()->whereDate('fecha_evento', today())->count();

    $misReservas = \App\Models\Reserva::where('user_id', auth()->id())
        ->activas()
        ->with('asiento.auditorio')
        ->orderBy('fecha_evento')
        ->limit(5)
        ->get();

    return view('dashboard', compact(
        'totalUsuarios', 'totalAuditorios', 'totalReservas', 'reservasHoy', 'misReservas'
    ));
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas de perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas de administración (solo admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::resource('usuarios', UserController::class)->except('show');
    Route::resource('auditorios', AuditorioController::class);
});

// Rutas de reservas (usuarios autenticados)
Route::middleware('auth')->group(function () {
    Route::get('/reservas', [ReservaController::class, 'index'])->name('reservas.index');
    Route::get('/reservas/crear/{auditorio}', [ReservaController::class, 'create'])->name('reservas.create');
    Route::post('/reservas', [ReservaController::class, 'store'])->name('reservas.store');
    Route::get('/reservas/{reserva}', [ReservaController::class, 'show'])->name('reservas.show');
    Route::patch('/reservas/{reserva}/cancelar', [ReservaController::class, 'cancelar'])->name('reservas.cancelar');

    // Lista de auditorios para usuarios (solo lectura)
    Route::get('/auditorios', [AuditorioController::class, 'index'])->name('auditorios.publico');
    Route::get('/auditorios/{auditorio}', [AuditorioController::class, 'show'])->name('auditorios.ver');
});

require __DIR__.'/auth.php';

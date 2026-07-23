<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\HabitacionController;
use App\Http\Controllers\ReservaController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\UsuarioController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Página principal -> Login
Route::get('/', function () {
    return view('auth.login');
});

// Autenticación
Auth::routes([
    'register' => false, // Deshabilita el registro
]);

// Todas las rutas protegidas
Route::middleware(['auth'])->group(function () {

    // Home
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Perfil
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');

    // Clientes
    Route::resource('clientes', ClienteController::class);

    // Tipos de Habitación
    Route::resource('tipo_habitaciones', TipoHabitacionController::class)
        ->parameters([
            'tipo_habitaciones' => 'tipoHabitacion'
        ]);

    // Habitaciones
    Route::resource('habitaciones', HabitacionController::class)
        ->parameters([
            'habitaciones' => 'habitacion'
        ]);

    // Reservas
    Route::resource('reservas', ReservaController::class);

    Route::post(
        'reservas/{reserva}/cambiar-estado',
        [ReservaController::class, 'cambiarEstado']
    )->name('reservas.cambiarEstado');

    // Pagos
    Route::resource('pagos', PagoController::class);

    // Usuarios
    Route::resource('usuarios', UsuarioController::class)
        ->except(['show']);

});
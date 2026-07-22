<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TipoHabitacionController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\HabitacionController;



Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::resource('clientes', ClienteController::class);
Route::resource('tipo_habitaciones', TipoHabitacionController::class)->parameters(['tipo_habitaciones' => 'tipoHabitacion']); 
//Route::resource('habitaciones', HabitacionController::class);



Route::resource('habitaciones', HabitacionController::class)->parameters([
    'habitaciones' => 'habitacion'
]);
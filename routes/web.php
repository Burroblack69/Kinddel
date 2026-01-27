<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CitaController;


Route::get('/', function () {
    return view('welcome');
})->name('home');


Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Grupo protegido
Route::middleware('auth')->group(function () {
    Route::get('/citas', [CitaController::class, 'index'])->name('citas.index');
    Route::post('/citas', [CitaController::class, 'store'])->name('citas.store');
    Route::get('/citas/{id}/edit', [CitaController::class, 'edit'])->name('citas.edit');
    Route::put('/citas/{id}', [CitaController::class, 'update'])->name('citas.update');
    Route::delete('/citas/{id}', [CitaController::class, 'destroy'])->name('citas.destroy');
    
    
    Route::post('/citas/{id}/responder', [CitaController::class, 'responder'])->name('citas.responder');
    Route::post('/mensajes', [App\Http\Controllers\CitaController::class, 'enviarMensaje'])->name('mensajes.store');
    // Rutas de Perfil
    Route::get('/perfil', [App\Http\Controllers\AuthController::class, 'editProfile'])->name('profile.edit');
    Route::put('/perfil', [App\Http\Controllers\AuthController::class, 'updateProfile'])->name('profile.update');
});
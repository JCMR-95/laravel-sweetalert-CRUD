<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('usuarios', [UsersController::class, 'index']);
Route::post('usuarios', [UsersController::class, 'crear']);
Route::get('editar-usuario/{id}', [UsersController::class, 'editar']);
Route::put('actualizar-usuario/{id}', [UsersController::class, 'actualizar']);
Route::get('eliminar-usuario/{id}', [UsersController::class, 'eliminar']);

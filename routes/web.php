<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CedulaController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;



Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');

Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Ruta para redireccionar a Google
Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('login.google');
Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback']);

// Grupo de rutas que requieren autenticación
Route::middleware(['auth'])->group(function () {
    Route::resource('categories', CategoryController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('products', ProductController::class);
    Route::resource('services', ServiceController::class);
    Route::resource('profile', ProfileController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('calendario', CalendarioController::class);
});

// Ruta por defecto que redirige al formulario de login
Route::get('/', function () {
    return view('auth.login');
})->name('home');  // Añadir un nombre a esta ruta podría ser útil.

// Ruta para recuperar contraseña
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

// Ruta para la vista de registro
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Ruta para enviar el formulario de registro
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Ruta para obtener datos por cédula
Route::get('/cedula/{cedula}', [CedulaController::class, 'obtenerDatos']);
Route::get('/user/{id}', [CedulaController::class, 'getInfoUserById']);


// Ruta para autenticar con Google


// Ruta de dashboard protegida (requiere autenticación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');



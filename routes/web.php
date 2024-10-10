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

Route::get('/', function () {
    return view('auth.login');
})->name('home');  // Dirige al home

// Ruta para recuperar contraseña
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('password.request');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Ruta para enviar el formulario de registro
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Ruta para obtener datos por cédula
Route::get('/cedula/{cedula}', [CedulaController::class, 'obtenerDatos']);
Route::get('/user/{id}', [CedulaController::class, 'getInfoUserById']);


// Ruta para tener el permiso de actualizar el perfil
Route::put('/{id}/update', [ProfileController::class, 'update'])->middleware('permission:profile.update')->name('profile.update');
Route::delete('/{id}/destroy', [ProfileController::class, 'destroy'])->middleware('permission:profile.destroy')->name('profile.destroy');
Route::post('/store', [ProfileController::class, 'store'])->middleware('permission:profile.store')->name('profile.store');
Route::get('/profile', [ProfileController::class, 'index'])->middleware('permission:view.index.profile')->name('profile.index');

//Ruta para tener permisos de roles
Route::put('/{id}/update', [RoleController::class, 'update'])->middleware('permission:role.update')->name('role.update');
Route::delete('/{id}/destroy', [RoleController::class, 'destroy'])->middleware('permission:role.destroy')->name('role.destroy');
Route::post('/store', [RoleController::class, 'store'])->middleware('permission:role.store')->name('role.store');
Route::get('/role', [RoleController::class, 'index'])->middleware('permission:view.index.role')->name('role.index');

//Ruta para tener permisos de productos
Route::put('/{id}/update', [ProductController::class, 'update'])->middleware('permission:product.update')->name('product.update');
Route::delete('/{id}/destroy', [ProductController::class, 'destroy'])->middleware('permission:product.destroy')->name('product.destroy');
Route::post('/store', [ProductController::class, 'store'])->middleware('permission:product.store')->name('product.store');
Route::get('/product', [ProductController::class, 'index'])->middleware('permission:view.index.product')->name('product.index');

//Ruta para tener permisos para los employees
Route::put('/{id}/update', [EmployeeController::class, 'update'])->middleware('permission:employee.update')->name('employee.update');
Route::delete('/{id}/destroy', [EmployeeController::class, 'destroy'])->middleware('permission:employee.destroy')->name('employee.destroy');
Route::post('/store', [EmployeeController::class, 'store'])->middleware('permission:employee.store')->name('employee.store');
Route::get('/employee', [EmployeeController::class, 'index'])->middleware('permission:view.index.employee')->name('employee.index');

//Ruta para tener permisos para los services
Route::put('/{id}/update', [ServiceController::class, 'update'])->middleware('permission:service.update')->name('service.update');
Route::delete('/{id}/destroy', [ServiceController::class, 'destroy'])->middleware('permission:service.destroy')->name('service.destroy');
Route::post('/store', [ServiceController::class, 'store'])->middleware('permission:service.store')->name('service.store');
Route::get('/service', [ServiceController::class, 'index'])->middleware('permission:view.index.service')->name('service.index');

// Ruta de dashboard protegida (requiere autenticación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');



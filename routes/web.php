<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarioController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CedulaController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ServiceController;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

Auth::routes();

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
    Route::get('/workOrder', [CalendarioController::class, 'workOrder'])->name('calendario.ordenes');
    Route::patch('/workorders/{workorder}/authorize', [CalendarioController::class, 'authorizeWorkOrder'])->name('workorders.authorize');
    Route::put('/workOrder/{workOrderId}', [CalendarioController::class, 'update'])->name('calendario.update');
    Route::resource('reports', ReportController::class);
    Route::patch('/generate-pdf/{id}', [ReportController::class, 'generatePdf'])->name('generate.pdf');
    Route::get('/serve-pdf/{id}', [ReportController::class, 'servePdf'])->name('serve.pdf');
    Route::patch('/reports/{id}/send-email', [ReportController::class, 'sendEmail'])->name('reports.send-email');
    Route::get('/edit-profile/{id}', [AuthController::class, 'edit'])->name('edit.profile');
    Route::put('/update-profile/{id}', [AuthController::class, 'update'])->name('update.profile');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('change.password');
    Route::get('/settings/profile/{id}', [AuthController::class, 'profile'])->name('settings.profile');
    Route::patch('/reports/{report}/remove-product/{usedProduct}', [ReportController::class, 'removeProduct'])->name('reports.remove-product');




});


Route::get('/', function () {
    return view('auth.login');
})->name('home');  // Dirige al home

Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('password/reset', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Ruta para enviar el formulario de registro
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

// Ruta para obtener datos por cédula
Route::get('/cedula/{cedula}', [CedulaController::class, 'obtenerDatos']);
Route::get('/user/{id}', [CedulaController::class, 'getInfoUserById']);
Route::get('/events', [CedulaController::class, 'getEvents'])->name('get.events');
Route::get('/getWorkOrderById/{id}', [CedulaController::class, 'JsonWorkOrder'])->name('get.workOrder');
Route::get('/getImage/{id}', [CedulaController::class, 'getUserImageById'])->name('get.image');





















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

//Ruta para tener permisos de calendario
Route::delete('/{id}/destroy', [CalendarioController::class, 'destroy'])->middleware('permission:calendario.destroy')->name('calendario.destroy');
Route::post('/store', [CalendarioController::class, 'store'])->middleware('permission:calendario.store')->name('calendario.store');
Route::get('/workOrder', [CalendarioController::class, 'workOrder'])->middleware('permission:view.index.ordenes')->name('calendario.ordenes');
Route::put('/workOrder/{workOrderId}', [CalendarioController::class, 'update'])->middleware('permission:calendario.update')->name('calendario.update');
Route::get('/calendario', [CalendarioController::class, 'index'])->middleware('permission:view.index.calendar')->name('calendario.index');

//Ruta para tener permisos de reportes

// Ruta de dashboard protegida (requiere autenticación)
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');



<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NormalUserController;
use App\Http\Controllers\Normal\Auth\LoginController as NormalLoginController;
use App\Http\Controllers\Normal\DashboardController;

Route::get('/', function () {
    return view('welcome');
});


Route::prefix('admin')->name('admin.')->group(function () {
    // Guest routes
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AdminLoginController::class, 'showLoginForm'])->name('login');
        Route::post('login', [AdminLoginController::class, 'login'])->name('login.post');

        Route::get('register', [AdminRegisterController::class, 'showRegistrationForm'])->name('register');
        Route::post('register', [AdminRegisterController::class, 'register'])->name('register.post');
    });

    // Authenticated admin routes
    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('logout', [AdminLoginController::class, 'logout'])->name('logout');
    });
});

// Admin can manage users
Route::middleware('auth:admin')->group(function () {
    Route::get('/admin/users', [NormalUserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/create', [NormalUserController::class, 'create'])->name('admin.users.create');
    Route::post('/admin/users/store', [NormalUserController::class, 'store'])->name('admin.users.store');
    Route::get('/admin/users/{user}/edit', [NormalUserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [NormalUserController::class, 'update'])->name('admin.users.update');
    Route::delete('/admin/users/{user}', [NormalUserController::class, 'destroy'])->name('admin.users.destroy');
    Route::get('/admin/users/{user}/history', [NormalUserController::class, 'history'])->name('admin.users.history');
    Route::get('/admin/users/{user}/document/{chat}', [NormalUserController::class, 'viewDocument'])->name('admin.users.document');
});

// Normal user login
Route::get('/user/login', [NormalLoginController::class, 'showLoginForm'])->name('normal.login');
Route::post('/user/login', [NormalLoginController::class, 'login'])->name('normal.login.post');
Route::post('/user/logout', [NormalLoginController::class, 'logout'])->name('normal.logout');

// Normal dashboard
Route::get('/user/dashboard', [DashboardController::class, 'index'])->name('normal.dashboard')->middleware('auth:normaluser');
use App\Http\Controllers\Normal\DocumentController;

Route::get('/user/documents', [DocumentController::class, 'index'])->name('normal.documents')->middleware('auth:normaluser');
Route::get('/user/settings', function() { return view('normal.settings'); })->name('normal.settings')->middleware('auth:normaluser');
Route::post('/user/prompt', [DashboardController::class, 'prompt'])->name('normal.prompt')->middleware('auth:normaluser');
Route::get('/user/document/{chat}', [DocumentController::class, 'show'])->name('normal.document.show')->middleware('auth:normaluser');
Route::put('/user/document/{chat}', [DocumentController::class, 'update'])->name('normal.document.update')->middleware('auth:normaluser');
Route::post('/user/document/regenerate', [DocumentController::class, 'regenerateSection'])->name('normal.document.regenerate')->middleware('auth:normaluser');
Route::delete('/user/document/{chat}', [DocumentController::class, 'destroy'])->name('normal.document.destroy')->middleware('auth:normaluser');
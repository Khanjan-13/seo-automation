<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\Admin\Auth\LoginController as AdminLoginController;
use App\Http\Controllers\Admin\Auth\RegisterController as AdminRegisterController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\NormalUserController;
use App\Http\Controllers\Normal\Auth\LoginController as NormalLoginController;
use App\Http\Controllers\Normal\DashboardController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\Normal\DocumentController;

Route::get('/', function () {
    return view('welcome');
});

// Public share route (no authentication required)
Route::get('/share/{token}', [ShareController::class, 'view'])->name('share.view');


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

Route::get('/user/documents', [DocumentController::class, 'index'])->name('normal.documents')->middleware('auth:normaluser');
Route::get('/user/settings', [DashboardController::class, 'settings'])->name('normal.settings')->middleware('auth:normaluser');
Route::post('/user/prompt', [DashboardController::class, 'prompt'])->name('normal.prompt')->middleware('auth:normaluser');
Route::get('/user/document/{chat}', [DocumentController::class, 'show'])->name('normal.document.show')->middleware('auth:normaluser');
Route::put('/user/document/{chat}', [DocumentController::class, 'update'])->name('normal.document.update')->middleware('auth:normaluser');
Route::post('/user/document/regenerate', [DocumentController::class, 'regenerateSection'])->name('normal.document.regenerate')->middleware('auth:normaluser');
Route::delete('/user/document/{chat}', [DocumentController::class, 'destroy'])->name('normal.document.destroy')->middleware('auth:normaluser');
Route::post('/user/document/{chat}/share', [DocumentController::class, 'generateShare'])->name('normal.document.share')->middleware('auth:normaluser');

// Templates page
Route::get('/user/templates', [\App\Http\Controllers\TemplateController::class, 'page'])->name('normal.templates')->middleware('auth:normaluser');

// Template API routes
Route::middleware('auth:normaluser')->prefix('user/templates/api')->name('normal.templates.')->group(function () {
    Route::get('/', [\App\Http\Controllers\TemplateController::class, 'index'])->name('index');
    Route::post('/', [\App\Http\Controllers\TemplateController::class, 'store'])->name('store');
    Route::get('/{id}', [\App\Http\Controllers\TemplateController::class, 'show'])->name('show');
    Route::put('/{id}', [\App\Http\Controllers\TemplateController::class, 'update'])->name('update');
    Route::delete('/{id}', [\App\Http\Controllers\TemplateController::class, 'destroy'])->name('destroy');
});
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\Auth\AdminLoginController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

// Customer-Facing Routes (Public)
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

Route::get('/menu', function () {
    return Inertia::render('Customer/pages/Menu');
})->name('menu');

Route::get('/reservation', function () {
    return Inertia::render('Customer/pages/Reservation');
})->name('reservation');

Route::get('/location', function () {
    return Inertia::render('Customer/pages/Location');
})->name('location');

Route::get('/gift-cards', function () {
    return Inertia::render('Customer/pages/GiftCard');
})->name('gift-cards');

Route::get('/membership', function () {
    return Inertia::render('Customer/pages/Membership');
})->name('membership');

Route::get('/catering', function () {
    return Inertia::render('Customer/pages/Catering');
})->name('catering');

// Public API Route for Menu Items
Route::get('/api/food', function () {
    return \App\Models\Food::paginate(10);
})->name('api.food');

// Authentication Routes (Regular Users/Customers)
Route::get('/login', function () {
    return Inertia::render('auth/Login');
})->name('login');

Route::post('/login', [LoginController::class, 'login']);

Route::get('/register', function () {
    return Inertia::render('auth/Register');
})->name('register');

Route::post('/register', [RegisterController::class, 'register']);

// Customer Protected Routes
Route::middleware('auth:customer')->group(function () {
    Route::post('/api/orders', [OrderController::class, 'store'])->name('api.orders.store');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', [AuthController::class, 'user'])->name('user');
});

// Admin Login Routes
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AdminLoginController::class, 'login']);
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

// Admin Routes (Protected by auth)
Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

    // Employee Management
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employees', 'index')->name('admin.employees');
        Route::get('/employees/create', 'create')->name('admin.employees.create');
        Route::post('/employees', 'store')->name('admin.employees.store');
        Route::get('/employees/{id}/edit', 'edit')->name('admin.employees.edit');
        Route::put('/employees/{id}', 'update')->name('admin.employees.update');
        Route::delete('/employees/{id}', 'destroy')->name('admin.employees.destroy');
    });

    // Food Management
    Route::controller(FoodController::class)->group(function () {
        Route::get('/food', 'index')->name('admin.food');
        Route::get('/food/create', 'create')->name('admin.food.create');
        Route::post('/food', 'store')->name('admin.food.store');
        Route::get('/food/{id}/edit', 'edit')->name('admin.food.edit');
        Route::put('/food/{id}/update', 'update')->name('admin.food.update');
        Route::delete('/food/{id}', 'destroy')->name('admin.food.destroy');
    });

    // Order Management
    Route::controller(OrderController::class)->group(function () {
        Route::get('/orders', 'index')->name('admin.orders');
        Route::get('/orders/{id}', 'show')->name('admin.orders.show');
        Route::put('/orders/{id}', 'update')->name('admin.orders.update');
    });
});

// Temporary Customer Login (Remove after proper login is implemented)
Route::get('/login-customer', function () {
    Auth::guard('customer')->login(\App\Models\Customer::first());
    return redirect('/menu');
})->name('login-customer');
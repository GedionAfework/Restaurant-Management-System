<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\KitchenController;
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
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('permission:dashboard-view')
        ->name('admin.dashboard');

    // Employee Management
    Route::controller(EmployeeController::class)->middleware('permission:employees-view')->group(function () {
        Route::get('/employees', 'index')->name('admin.employees');
        Route::get('/employees/create', 'create')->middleware('permission:employees-create')->name('admin.employees.create');
        Route::post('/employees', 'store')->middleware('permission:employees-create')->name('admin.employees.store');
        Route::get('/employees/{id}/edit', 'edit')->middleware('permission:employees-edit')->name('admin.employees.edit');
        Route::put('/employees/{id}', 'update')->middleware('permission:employees-edit')->name('admin.employees.update');
        Route::delete('/employees/{id}', 'destroy')->middleware('permission:employees-delete')->name('admin.employees.destroy');
    });

    // Menu Category Management
    Route::resource('menu-categories', MenuCategoryController::class)
        ->middleware('permission:menu-view');

    // Food Management
    Route::controller(FoodController::class)->middleware('permission:menu-view')->group(function () {
        Route::get('/food', 'index')->name('admin.food');
        Route::get('/food/create', 'create')->middleware('permission:menu-create')->name('admin.food.create');
        Route::post('/food', 'store')->middleware('permission:menu-create')->name('admin.food.store');
        Route::get('/food/{id}/edit', 'edit')->middleware('permission:menu-edit')->name('admin.food.edit');
        Route::put('/food/{id}/update', 'update')->middleware('permission:menu-edit')->name('admin.food.update');
        Route::delete('/food/{id}', 'destroy')->middleware('permission:menu-delete')->name('admin.food.destroy');
    });

    // Order Management
    Route::controller(OrderController::class)->middleware('permission:orders-view')->group(function () {
        Route::get('/orders', 'index')->name('admin.orders.index');
        Route::get('/orders/{id}', 'show')->name('admin.orders.show');
        Route::put('/orders/{id}', 'update')->middleware('permission:orders-update')->name('admin.orders.update');
        Route::post('/orders', 'store')->middleware('permission:orders-create')->name('admin.orders.store');
    });

    // Kitchen Display System
    Route::controller(KitchenController::class)->middleware('permission:orders-view')->group(function () {
        Route::get('/kitchen', 'index')->name('admin.kitchen.index');
        Route::post('/kitchen/orders/{id}/status', 'updateStatus')->middleware('permission:orders-update')->name('admin.kitchen.update-status');
        Route::get('/kitchen/orders', 'getOrders')->name('admin.kitchen.get-orders');
    });

    // Role Management
    Route::resource('roles', RoleController::class)
        ->except(['show'])
        ->middleware('permission:roles-view');

    // Permission Management
    Route::resource('permissions', PermissionController::class)
        ->middleware('permission:permissions-manage');

    // Table Management
    Route::controller(TableController::class)->middleware('permission:tables-view')->group(function () {
        Route::get('/tables', 'index')->name('admin.tables.index');
        Route::get('/tables/create', 'create')->middleware('permission:tables-manage')->name('admin.tables.create');
        Route::post('/tables', 'store')->middleware('permission:tables-manage')->name('admin.tables.store');
        Route::get('/tables/{id}', 'show')->name('admin.tables.show');
        Route::get('/tables/{id}/edit', 'edit')->middleware('permission:tables-manage')->name('admin.tables.edit');
        Route::put('/tables/{id}', 'update')->middleware('permission:tables-manage')->name('admin.tables.update');
        Route::delete('/tables/{id}', 'destroy')->middleware('permission:tables-manage')->name('admin.tables.destroy');
        Route::post('/tables/{id}/release', 'release')->middleware('permission:tables-assign')->name('admin.tables.release');
        Route::get('/tables/layout', 'layout')->name('admin.tables.layout');
        Route::post('/tables/positions', 'updatePositions')->middleware('permission:tables-manage')->name('admin.tables.update-positions');
        Route::post('/tables/{id}/assign-order', 'assignToOrder')->middleware('permission:tables-assign')->name('admin.tables.assign-order');
    });
});

// Temporary Customer Login (Remove after proper login is implemented)
Route::get('/login-customer', function () {
    Auth::guard('customer')->login(\App\Models\Customer::first());
    return redirect('/menu');
})->name('login-customer');
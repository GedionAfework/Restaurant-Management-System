<?php

// use Illuminate\Support\Facades\Route;
// use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Inertia\Inertia;

// Authentication Routes
// Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
// Route::post('/login', [AuthController::class, 'login']);
// Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');

// // Dashboard (Protected)
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard');
//     });

//     // Food Routes
//     Route::get('/foods', [FoodController::class, 'index']);
//     Route::post('/foods', [FoodController::class, 'store']);
//     Route::get('/foods/{id}', [FoodController::class, 'show']);
//     Route::put('/foods/{id}', [FoodController::class, 'update']);
//     Route::delete('/foods/{id}', [FoodController::class, 'destroy']);

//     // Order Routes
//     Route::get('/orders', [OrderController::class, 'index']);
//     Route::post('/orders', [OrderController::class, 'store']);
//     Route::get('/orders/{id}', [OrderController::class, 'show']);
// });

// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         return Inertia::render('Dashboard', [
//             'user' => auth()->user(),
//         ]);
//     });
// });
Route::get('/', function () {
    return Inertia::render('Home');  // Render the Home.vue page if needed
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
Route::get('/login', function () {
    return Inertia::render('auth/Login');
})->name('login');
Route::get('/register', function () {
    return Inertia::render('auth/Register');
})->name('register');

// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth');
// Route::get('/register', [RegisterController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);


// use App\Http\Controllers\Admin\EmployeeController;
// // use App\Http\Controllers\Admin\FoodController;
// use App\Http\Controllers\Admin\OrderController;
// use App\Http\Controllers\Admin\DashboardController;
// use Illuminate\Support\Facades\Route;

// Admin Dashboard Route
// Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

// // Admin Employee Management Routes
// Route::prefix('admin/employees')->name('admin.employees.')->group(function () {
//     Route::get('/', [EmployeeController::class, 'index'])->name('index');   // List employees
//     Route::get('/create', [EmployeeController::class, 'create'])->name('create');  // Create employee form
//     Route::post('/store', [EmployeeController::class, 'store'])->name('store');  // Store employee data
//     Route::get('/{id}/edit', [EmployeeController::class, 'edit'])->name('edit');  // Edit employee form
//     Route::put('/{id}/update', [EmployeeController::class, 'update'])->name('update');  // Update employee
//     Route::delete('/{id}/destroy', [EmployeeController::class, 'destroy'])->name('destroy');  // Delete employee
// });

// // Admin Food Management Routes
// Route::prefix('admin/food')->name('admin.food.')->group(function () {
//     Route::get('/', [FoodController::class, 'index'])->name('index');   // List food items
//     Route::get('/create', [FoodController::class, 'create'])->name('create');  // Create food item form
//     Route::post('/store', [FoodController::class, 'store'])->name('store');  // Store food item data
//     Route::get('/{id}/edit', [FoodController::class, 'edit'])->name('edit');  // Edit food item form
//     Route::put('/{id}/update', [FoodController::class, 'update'])->name('update');  // Update food item
//     Route::delete('/{id}/destroy', [FoodController::class, 'destroy'])->name('destroy');  // Delete food item
// });

// // Admin Order Management Routes
// Route::prefix('admin/orders')->name('admin.orders.')->group(function () {
//     Route::get('/', [OrderController::class, 'index'])->name('index');  // List all orders
//     Route::get('/{id}/show', [OrderController::class, 'show'])->name('show');  // View order details
//     Route::put('/{id}/update', [OrderController::class, 'update'])->name('update');  // Update order status
// });


// use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\FoodController;

// Admin Routes (Protected)
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {

    // Dashboard Route
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Employee Management Routes
    Route::controller(EmployeeController::class)->group(function () {
        Route::get('/employees', 'index')->name('admin.employees.index');
        Route::get('/employees/create', 'create')->name('admin.employees.create');
        Route::post('/employees', 'store')->name('admin.employees.store');
        Route::get('/employees/{id}/edit', 'edit')->name('admin.employees.edit');
        Route::put('/employees/{id}', 'update')->name('admin.employees.update');
        Route::delete('/employees/{id}', 'destroy')->name('admin.employees.destroy');
    });

    // Food Management Routes
    Route::controller(FoodController::class)->group(function () {
        Route::get('/food', 'index')->name('admin.food.index');
        Route::get('/food/create', 'create')->name('admin.food.create');
        Route::post('/food', 'store')->name('admin.food.store');
        Route::get('/food/{id}/edit', 'edit')->name('admin.food.edit');
        Route::put('/food/{id}', 'update')->name('admin.food.update');
        Route::delete('/food/{id}', 'destroy')->name('admin.food.destroy');
    });

});

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
use App\Http\Controllers\FoodController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Auth\LoginController;
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

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'user' => auth()->user(),
        ]);
    });
});
Route::get('/', function () {
    return Inertia::render('Home');  // Render the Home.vue page if needed
});

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

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth');
Route::get('/user', [AuthController::class, 'user'])->middleware('auth');
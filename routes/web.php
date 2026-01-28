<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Redirect Root
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
|--------------------------------------------------------------------------
| Guest Routes (Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit')->middleware('throttle:5,1');
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {

    Route::resource('/roles', RoleController::class)->except('show');
    /**
     * Logout (POST for CSRF protection)
     */
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /**
     * Dashboard
     */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDelete'])
    ->name('products.bulk-delete');
    /**
     * Product Management (Policy Protected)
     */
    Route::resource('products', ProductController::class)
        ->except(['show']);


});

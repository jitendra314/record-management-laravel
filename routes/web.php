<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ProductController;


Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*  Guest Routes (Authentication) */

Route::middleware('guest')->group(function () {

    Route::get('/login', [AuthController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [AuthController::class, 'login'])
        ->name('login.submit')->middleware('throttle:5,1');
});

/*  Authenticated Routes */

Route::middleware('auth')->group(function () {

    Route::resource('/roles', RoleController::class)->except('show');
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');


    Route::delete('/products/bulk-delete', [ProductController::class, 'bulkDelete'])
    ->name('products.bulk-delete');

    Route::resource('products', ProductController::class)
        ->except(['show']);


});

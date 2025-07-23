<?php

use App\Models\Product\Categorie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\Client\CompanyController;
use App\Http\Controllers\Product\CategorieController;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    // Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    // Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    // Route::post('/me', [AuthController::class, 'me'])->middleware(['auth:api'])->name('me');
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::resource('roles', RoleController::class);
    Route::post('users/{id}',[UserController::class, 'update']);
    Route::resource('users', UserController::class);

    Route::resource('company', CompanyController::class);

    Route::resource('categories', CategorieController::class);
    Route::post('categories/{id}', [CategorieController::class, 'update']);

    Route::resource('products', ProductController::class);

    Route::get('products/config', [ProductController::class, 'config']);
    Route::post('products/{id}', [ProductController::class, 'update']);


});

<?php

use App\Models\Product\Categorie;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Role\RoleController;
use App\Http\Controllers\user\UserController;
use App\Http\Controllers\Client\CompanyController;
use App\Http\Controllers\Client\ClientController;
use App\Http\Controllers\Product\CategorieController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\Product\ProductBatchController;
use App\Http\Controllers\sale\SaleController;
use App\Http\Controllers\Zone\ZoneController;

Route::group([
    'prefix' => 'auth'
], function ($router) {
    // Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    // Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api')->name('logout');
    Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');
    Route::post('/refresh-token', [AuthController::class, 'refreshToken'])->middleware('auth:api')->name('refresh-token');
    // Route::post('/me', [AuthController::class, 'me'])->middleware(['auth:api'])->name('me');
});

Route::group([
    'middleware' => 'auth:api'
], function ($router) {
    Route::resource('roles', RoleController::class);
    Route::post('users/{id}',[UserController::class, 'update']);
    Route::resource('users', UserController::class);

    Route::resource('company', CompanyController::class);

    // Client routes
    Route::resource('clients', ClientController::class);
    Route::post('clients/bulk-import', [ClientController::class, 'bulkImport']); // Importación masiva
    Route::get('clients/document/search', [ClientController::class, 'getByDocument']); // Buscar por documento
    Route::post('clients/{id}/toggle-state', [ClientController::class, 'toggleState']); // Cambiar estado
    Route::post('clients/{id}/restore', [ClientController::class, 'restore']); // Restaurar cliente eliminado

    Route::resource('categories', CategorieController::class);
    Route::post('categories/{id}', [CategorieController::class, 'update']);

    Route::get('products/config', [ProductController::class, 'config']);
    Route::resource('products', ProductController::class);
    Route::post('products/{id}', [ProductController::class, 'update']);

    // Product Batches routes
    Route::get('products/{product}/batches', [ProductBatchController::class, 'index']); // Listar lotes de un producto específico
    Route::post('product-batches', [ProductBatchController::class, 'store']); // Crear lote
    Route::get('product-batches/{id}', [ProductBatchController::class, 'show']); // Ver lote específico
    Route::put('product-batches/{id}', [ProductBatchController::class, 'update']); // Actualizar lote
    Route::delete('product-batches/{id}', [ProductBatchController::class, 'destroy']); // Eliminar lote
    Route::get('product-batches/expiring-soon', [ProductBatchController::class, 'expiringSoon']); // Lotes por vencer

    // Zone routes
    Route::resource('zones', ZoneController::class);
    Route::get('zones-trashed', [ZoneController::class, 'trashed']);
    Route::post('zones/{id}/restore', [ZoneController::class, 'restore']);

    // Sale routes
    Route::get('sales/config', [SaleController::class, 'config']);
    Route::resource('sales', SaleController::class);
    Route::post('sales/{id}', [SaleController::class, 'update']);

});

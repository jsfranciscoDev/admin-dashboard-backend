<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [RegisteredUserController::class, 'register'])->name('register');
Route::post('/login', [AuthenticatedSessionController::class, 'login'])->name('login');

Route::middleware(['auth:sanctum'])->group(function () {
    // Route for accessing UserController methods
    Route::get('/user', [UserController::class, 'index']);
    
    Route::get('/get-products', [ProductController::class, 'getProducts'])->name('getProducts');
    Route::get('/get-product-categories', [ProductController::class, 'getProductsCategories'])->name('getProductsCategories');
    
    Route::delete('/delete-product/{id}', [ProductController::class, 'deleteProduct'])->name('deleteProduct');
 
    Route::post('/create-product', [ProductController::class, 'createProduct'])->name('createProduct');

    Route::post('/update-product', [ProductController::class, 'updateProduct'])->name('updateProduct');

    Route::post('/logout', [AuthenticatedSessionController::class, 'logout'])->name('logout');
    
    // Other routes that require Sanctum authentication can be added here
    // For example:
    // Route::post('/posts', [PostController::class, 'store']);
    // Route::delete('/posts/{id}', [PostController::class, 'destroy']);
});
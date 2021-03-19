<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FavoriteProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersCartController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function () {
//     Route::post('/cart', [UsersCartController::class, 'index']);
// });

Route::group(["middleware" => "auth:sanctum"], function () {


    // Only For Seller (id = user_id)
    Route::group(["prefix" => '/users/{id}'], function () {
        // Adding product (Only for Seller)
        Route::post('/products', [ProductController::class, 'store']);
        Route::delete('/products/{productId}', [ProductController::class, 'destroy']);
        Route::put('/products/{productId}', [ProductController::class, 'update']);
    });

    // Category (for seller)
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::delete('/categories/{categoryId}', [CategoryController::class, 'destroy']);
    Route::put('/categories/{categoryId}', [CategoryController::class, 'update']);

    // Get all product 
    Route::get('/products', [ProductController::class, 'index']);

    // Get all categories
    Route::get('/categories', [CategoryController::class, 'index']);

    // id = user_id
    Route::group(["prefix" => '/users/{id}'], function () {

        // Update user
        Route::put('/', [UserController::class, 'update']);

        // Update user profile pict
        Route::post('/updatePhoto', [UserController::class, 'profilePictureUpload']);

        // Get user cart
        Route::get('/cart', [UsersCartController::class, 'index']);
        // Adding product to cart
        Route::post('/cart', [UsersCartController::class, 'store']);
        // Delete 1 item of a product from cart
        Route::delete('/cart/{productId}', [UsersCartController::class, 'destroy']);
        // Delete 1 product from cart
        Route::delete('/cart/{productId}/all', [UsersCartController::class, 'deleteProduct']);

        // Get list of product based on User
        Route::get('/products', [ProductController::class, 'indexBasedOnUserId']);

        // Get user Favorite Products
        Route::get('/favorite', [FavoriteProductController::class, 'index']);
        // Add favorite product
        Route::post('/favorite', [FavoriteProductController::class, 'store']);
        // Delete favorite product
        Route::delete('/favorite/{productId}', [FavoriteProductController::class, 'destroy']);
    });
});
// Route::post('token', [AuthController::class, "requestToken"]);
Route::post('register', [AuthController::class, "register"]);
Route::post('login', [AuthController::class, "login"]);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
// Route::group(["middleware"])
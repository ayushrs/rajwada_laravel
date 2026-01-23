<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\HomeDataController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\ProductController;
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
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Public home page data (no auth required)
Route::get('/homedata', [HomeDataController::class, 'homedata']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/states', [LocationController::class, 'statesWithCities']);

// Cities by state id
Route::get('/cities/{state_id}', [LocationController::class, 'citiesByState']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::post('/cart/add', [CartController::class, 'addToCart']);
    Route::get('/get_cart', [CartController::class, 'getCart']);
    Route::post('/cart/remove', [CartController::class, 'removeFromCart']);
});

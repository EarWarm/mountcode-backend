<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Product\ProductCategoryController;
use App\Http\Controllers\Product\ProductController;
use App\Http\Controllers\User\ChangePasswordController;
use App\Http\Controllers\User\PaymentController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\UserProductController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('unauthorized')->group(function () {
    Route::post('/login', LoginController::class);
    Route::post('/register', RegisterController::class);
    Route::post('/forgot-password', ForgotPasswordController::class);
    Route::post('/reset-password', ResetPasswordController::class)->name('reset.password');
});

Route::middleware('auth:api')->group(function () {
    Route::post('/logout', LogoutController::class);
    Route::get('/user', UserController::class);

    Route::post('/products/{product}', [ProductController::class, 'buy']);

    Route::post('/user/change-password', ChangePasswordController::class);
    Route::post('/user/payment', PaymentController::class);
    Route::get('/user/products', UserProductController::class);
    Route::get('/user/products/{userProduct}', [UserProductController::class, 'resources']);
    Route::get('/user/products/{userProduct}/{uuid}', [UserProductController::class, 'downloadResource']);
});

Route::get('/categories', ProductCategoryController::class);
Route::get('/products', ProductController::class);

<?php

use App\Http\Controllers\Admin\Product\ProductCategoryCrudController;
use App\Http\Controllers\Admin\Product\ProductCrudController;
use App\Http\Controllers\Admin\Product\ProductResourceCrudController;
use App\Http\Controllers\Admin\User\PermissionCrudController;
use App\Http\Controllers\Admin\User\RoleCrudController;
use App\Http\Controllers\Admin\User\UserCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array)config('backpack.base.web_middleware', 'web'),
        (array)config('backpack.base.middleware_key', 'admin')
    )
], function () {
    Route::crud('permission', PermissionCrudController::class);
    Route::crud('role', RoleCrudController::class);
    Route::crud('user', UserCrudController::class);
    Route::crud('product-category', ProductCategoryCrudController::class);
    Route::crud('product', ProductCrudController::class);
    Route::crud('product-resource', ProductResourceCrudController::class);
});

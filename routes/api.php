<?php

use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\RatingController;


Route::post('v1/login', [AuthController::class, 'login'])->name('api.login');


Route::name('api.')
    ->middleware('auth:sanctum')
    ->prefix('v1')
    ->group(function () {

        Route::apiResource('products', ProductController::class);
        Route::apiResource('user-ratings', RatingController::class);

        Route::get('ratings', [RatingController::class, 'productRating']);




        Route::post('/logout', [AuthController::class, 'logout']);
    });

<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PurchaseController;

// RESTful routes for purchases
Route::apiResource('purchases', PurchaseController::class);
Route::apiResource('products', ProductController::class);

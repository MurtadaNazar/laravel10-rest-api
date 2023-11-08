<?php

use Illuminate\Support\Facades\Route;


use App\Http\Controllers\PurchaseController;

// RESTful routes for purchases
Route::resource('purchases', PurchaseController::class);

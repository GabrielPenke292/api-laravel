<?php

use App\Http\Controllers\Api\v1\InvoiceController;
use App\Http\Controllers\Api\v1\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


Route::prefix("v1")->group(function () {
    Route::prefix("users")->group(function () {
        Route::get("/", [UserController::class, "index"]);
        Route::get("/{id}", [UserController::class, "show"]);
    });

    Route::prefix("invoices")->group(function () {
        Route::get("/", [InvoiceController::class, "index"]);
        Route::get("/{id}", [InvoiceController::class, "show"]);
    });
});
<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);


});

Route::middleware(['auth:api', 'role'])->group(function() {

    Route::middleware(['scope:owner,admin'])->group(function() {
        Route::get('/not_available_users', [UserController::class, 'getNotAvailableUsers']);
        Route::post('/user_account_decision', [UserController::class, 'userAccountDecision']);
    });

});

Route::get('/user/{id}', [UserController::class, 'getUser']);

Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->name('login');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->name('register');
Route::post('/register_mechanic', [\App\Http\Controllers\AuthController::class, 'registerMechanic'])->name('register_mechanic');

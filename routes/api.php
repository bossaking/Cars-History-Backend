<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarManufacturers;
use App\Http\Controllers\CarModels;
use App\Http\Controllers\FuelTypes;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::middleware('auth:api')->group(function () {
    Route::get('/logout', [AuthController::class, 'logout']);


});

Route::middleware(['auth:api', 'role'])->group(function() {

    Route::middleware(['scope:owner,admin'])->group(function() {

        Route::get('/manufacturers/all', [CarManufacturers::class, 'getAll']);
        Route::get('/manufacturers/models', [CarManufacturers::class, 'manufacturerModels']);
        Route::post('/manufacturers/new', [CarManufacturers::class, 'newManufacturer']);
        Route::delete('/manufacturers/delete', [CarManufacturers::class, 'deleteManufacturer']);
        Route::put('/manufacturers/edit', [CarManufacturers::class, 'editManufacturer']);

        Route::get('/models/all', [CarModels::class, 'getAll']);
        Route::post('/models/new', [CarModels::class, 'newModel']);
        Route::delete('/models/delete', [CarModels::class, 'deleteModel']);
        Route::put('/models/edit', [CarModels::class, 'editModel']);

        Route::get('/fuel_types/all', [FuelTypes::class, 'getAll']);
        Route::post('/fuel_types/new', [FuelTypes::class, 'newFuelType']);
        Route::delete('/fuel_types/delete', [FuelTypes::class, 'deleteFuelType']);
        Route::put('/fuel_types/edit', [FuelTypes::class, 'editFuelType']);

        Route::get('/not_available_users', [UserController::class, 'getNotAvailableUsers']);
        Route::post('/user_account_decision', [UserController::class, 'userAccountDecision']);
    });

});

Route::get('/user/{id}', [UserController::class, 'getUser']);

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register_mechanic', [AuthController::class, 'registerMechanic'])->name('register_mechanic');

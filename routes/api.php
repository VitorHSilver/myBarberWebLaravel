<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/free-times', [AppointmentController::class, 'getAvailableTimes']);
Route::get('/reserved-times', [AppointmentController::class, 'getReservationsOfDay']);

Route::get('/', [AppointmentController::class, 'index']);
Route::get('/{id}', [AppointmentController::class, 'show']); 

Route::post('/', [AppointmentController::class, 'store']); 
Route::put('/{id}', [AppointmentController::class, 'update']); 
Route::delete('/{id}', [AppointmentController::class, 'destroy']); 

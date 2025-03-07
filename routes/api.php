<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;


Route::get('/all', [AppointmentController::class, 'getAllAppointment'])->name('free-times');

Route::get('/available-times', [AppointmentController::class, 'getAvailableTimes'])->name('available.times');

// Rota para buscar reservas do dia (pode ser mantida como API ou passada via props)
Route::get('/reservations', [AppointmentController::class, 'getReservationsOfDay'])->name('reservations');

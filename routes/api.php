<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;


Route::get('/all', [AppointmentController::class, 'getAllAppointment'])->name('free-times');

Route::get('/available-times', [AppointmentController::class, 'getAvailableTimes'])->name('available.times');

Route::get('/reservations', [AppointmentController::class, 'getReservationsOfDay'])->name('reservations');

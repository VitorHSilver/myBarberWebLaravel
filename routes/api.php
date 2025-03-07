<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;


Route::get('/all', [AppointmentController::class, 'getAllAppointment'])->name('free-times');



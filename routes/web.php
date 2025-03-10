<?php

use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfessionalController;
use Illuminate\Support\Facades\Route;


Route::get('/', [AppointmentController::class, 'index'])->name('home'); // Página inicial com o formulário

Route::middleware(['auth', 'verified'])->group(function () {
    // Redireciona para a dashboard correta com base no role
    Route::get('/dashboard', function () {
        /** @var \Illuminate\Contracts\Auth\Guard $auth */
        $auth = auth();
        /** @var User|null $user */
        $user = $auth->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        } elseif ($user->isProfessional()) {
            return redirect()->route('professional.dashboard');
        } else {
            return redirect()->route('user.dashboard');
        }
    })->name('dashboard');

    // Rotas do Admin
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/professionals', [AdminController::class, 'storeProfessional'])->name('admin.professionals.store');
        Route::delete('/admin/professionals/{id}', [AdminController::class, 'destroyProfessional'])->name('admin.professionals.destroy');
    });

    // Rotas do Profissional
    Route::middleware('professional')->group(function () {
        Route::get('/professional/dashboard', [ProfessionalController::class, 'index'])->name('professional.dashboard');
        Route::delete('/appointments/{id}/cancel', [AppointmentController::class, 'cancel'])->name('appointments.cancel');
    });

    // Rotas do Usuário
    Route::middleware('auth')->group(function () {
        Route::get('/user/dashboard', [AppointmentController::class, 'userDashboard'])->name('user.dashboard');
        Route::put('/appointments/{id}', [AppointmentController::class, 'update'])->name('appointments.update');
    });

    // Rotas de Perfil (já incluso no Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/user', function (Illuminate\Http\Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/logout', function () {
    /** @var \Illuminate\Support\Facades\Auth $auth */
    $auth = auth();
    $auth->logout();
    return redirect('/login');
})->name('logout');

Route::get('/auth/google', [\App\Http\Controllers\Auth\SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [\App\Http\Controllers\Auth\SocialAuthController::class, 'handleGoogleCallback']);

require __DIR__ . '/auth.php';

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;

class SocialAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            // Criar ou atualizar o usuário com base no e-mail
            $user = User::updateOrCreate(
                ['email' => strtolower($googleUser->email)],
                [
                    'name' => ucwords(strtolower($googleUser->name)),
                    'password' => bcrypt(Str::random(16)),
                ]
            );

            Appointment::where('email', $user->email)
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);

            // Logar o usuário
            Auth::login($user);

            return Inertia::location(route('dashboard'));
        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Erro ao autenticar com o Google.');
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AdminController extends Controller
{
    public function index()
    {
        $appointments = Appointment::with('user')->get();
        return inertia('Admin/Dashboard', [
            'appointments' => $appointments,
        ]);
    }

    public function listProfessionals()
    {

        if (!Gate::allows('manage-professionals', Auth::user())) {
            abort(403, 'Acesso negado.');
        }

        $professionals = User::where('role', 'professional')->get();
        return inertia('Admin/Professionals', [
            'professionals' => $professionals,
        ]);
    }

    public function storeProfessional(Request $request)
    {
        if (!Gate::allows('manage-professionals', Auth::user())) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'professional',
        ]);

        return redirect()->route('admin.professionals')->with('success', 'Profissional criado com sucesso!');
    }

    public function updateProfessional(Request $request, $id)
    {
        if (!Gate::allows('manage-professionals', Auth::user())) {
            abort(403, 'Acesso negado.');
        }

        $request->validate([
            'role' => 'required|in:user,professional,admin',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'role' => $request->role,
        ]);

        return redirect()->route('admin.professionals')->with('success', 'Papel do usuário atualizado com sucesso!');
    }

    public function destroyProfessional($id)
    {
        if (!Gate::allows('manage-professionals', Auth::user())) {
            abort(403, 'Acesso negado.');
        }

        $professional = User::findOrFail($id);
        $professional->delete();

        return redirect()->route('admin.professionals')->with('success', 'Profissional removido com sucesso!');
    }
}

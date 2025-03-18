<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;

class AppointmentController extends Controller
{
    public function index()
    {
        $timeSlot = AppointmentService::getAvailableTimesForToday();

        return Inertia::render('Agenda', [
            'timeSlot' => $timeSlot
        ]);
    }

    public function userDashboard()
    {
        $user = Auth::user();
        $appointments = Appointment::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(3)
            ->get();
        return inertia('User/Dashboard', [
            'appointments' => $appointments,
        ]);
    }

    public function show($id)
    {

        $appointment = Appointment::findOrFail($id);

        // Mantive a resposta JSON, já que parece ser um endpoint de API
        return response()->json($appointment, 200);

        // Se preferir usar Inertia, descomente esta parte:
        // return Inertia::render('AppointmentShow', [
        //     'appointment' => $appointment,
        // ]);
    }

    public function store(AppointmentRequest $request)
    {
        try {
            $userId = Auth::id();

            if (!$userId) {
                $user = User::where('email', strtolower($request->email))->first();
                $userId = $user ? $user->id : null;
            }

            Appointment::create([
                'name' => ucwords(strtolower($request->name)),
                'email' => strtolower($request->email),
                'fone' => $request->fone,
                'date' => $request->date,
                'time' => $request->time,
                'user_id' => $userId,
            ]);

            return redirect()->back()->with('success', 'Consulta marcada!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Erro ao criar agendamento: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);
        return Inertia::render('Appointment', [
            'appointment' => $appointment,
        ]);
    }

    public function update(AppointmentRequest $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);


            if (!Gate::allows('update-own-appointment', [Auth::user(), $appointment])) {
                abort(403, 'Acesso negado. Você não pode atualizar este agendamento.');
            }

            $userId = Auth::id();

            if (!$userId) {
                $user = User::where('email', strtolower($request->email))->first();
                $userId = $user ? $user->id : null;
            }

            $appointment->update([
                'name' => ucwords(strtolower($request->name)),
                'email' => strtolower($request->email),
                'fone' => $request->fone,
                'date' => $request->date,
                'time' => $request->time,
                'user_id' => $userId ?? $appointment->user_id,
            ]);

            return redirect()->back()->with('success', 'Consulta atualizada com sucesso!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar agendamento: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao atualizar agendamento.');
        }
    }

    public function destroy($id)
    {

        $appointment = Appointment::findOrFail($id);

        // Adicionei verificação de permissão (apenas admins podem deletar)
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Acesso negado. Apenas administradores podem deletar agendamentos.');
        }

        $appointment->delete();

        return redirect()->route('home')->with('success', 'Consulta excluída com sucesso!');
    }

    // API Endpoints

    public function getAvailableTimes(Request $request)
    {
        try {
            $date = $request->query('date');
            if (!$date || !Carbon::canBeCreatedFromFormat($date, 'Y-m-d')) {
                return response()->json(['message' => 'Data inválida.'], 400);
            }

            $availableTimes = AppointmentService::generateAvailableTimes($date);

            return response()->json(['times' => $availableTimes], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getReservationsOfDay(Request $request)
    {
        try {
            $date = $request->query('date');
            $reservations = Appointment::getReservationsByDate($date);
            return response()->json(['reserved' => $reservations, 'quantity' => $reservations->count()], 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }

    public function getAllAppointment()
    {
        try {
            $appointments = Appointment::getAllAppointments();
            return response()->json($appointments, 200);
        } catch (\Exception $e) {
            return response()->json(["message" => $e->getMessage()], 500);
        }
    }
}

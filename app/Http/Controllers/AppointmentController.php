<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Validation\ValidationException;


class AppointmentController extends Controller
{
    private $appointment;

    public function __construct(Appointment $appointment)
    {
        $this->appointment = $appointment;
    }

    public function index()
    {
        $timeSlot = AppointmentService::getAvailableTimesForToday();

        return Inertia::render('Agenda', [
            'timeSlot' => $timeSlot
        ]);
    }

    // Criar depois
    public function show($id)
    {
        $appointment = $this->appointment->getById($id);
        return response()->json($appointment, 200);

        // return Inertia::render('AppointmentShow', [
        //     'appointment' => $appointment,
        // ]);
    }


    public function store(AppointmentRequest $request)
    {
        try {
            $userId = auth()->id();

            if (!$userId) {
                $user = User::where('email', strtolower($request->email))->first();
                $userId = $user ? $user->id : null;
            }

            $this->appointment->create([
                'name' => ucwords(strtolower($request->name)),
                'email' => strtolower($request->email),
                'fone' => $request->fone,
                'date' => $request->date,
                'time' => $request->time,
                'user_id' => $user ? $user->id : null,
            ]);

            return redirect()->route('home')->with('success', 'Consulta marcada!');
        } catch (ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Erro ao criar agendamento: ' . $e->getMessage());
        }
    }

    public function update(AppointmentRequest $request, $id)
    {
        try {
            $appointment = $this->appointment->findOrFail($id);

            $userId = auth()->id();

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
                'user_id' =>  $userId ?? $appointment->user_id,
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
        $appointment = $this->appointment->findOrFail($id);
        $appointment->delete();

        return redirect()->route('home')->with('success', 'Consulta excluída com sucesso!');
    }

    
    //api

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
            $reservations = $this->appointment->getReservationsByDate($date);
            return response()->json(['reserved' => $reservations, 'quantity' => $reservations->count()], 200);
        } catch (\Exception $e) {
            return response()->json(["message"  => $e->getMessage()], 500);
        }
    }

    public function getAllAppointment()
    {
        try {

            $appointments = $this->appointment->getAllAppointments();
            return response()->json($appointments, 200);
        } catch (\Exception $e) {
            return response()->json(["message"  => $e->getMessage()], 500);
        }
    }
}

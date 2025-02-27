<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\User;
use App\Services\AppointmentService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AppointmentController extends Controller
{


    public function index()
    {
        $timeSlot = $this->getAvailableTimesForToday();

        return Inertia::render('Agenda', [
            'timeSlot' => $timeSlot
        ]);
    }

    // Criar depois
    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment, 200);

        // return Inertia::render('AppointmentShow', [
        //     'appointment' => $appointment,
        // ]);
    }


    public function store(Request $request)
    {
        try {
            $messages = [
                'name.required' => ' nome é obrigatório.',
                'name.min' => ' nome deve ter pelo menos 2 caracteres.',
                'name.max' => ' nome deve ter no máximo 255 caracteres.',
                'date.required' => '  data é obrigatória.',
                'fone.required' => ' telefone é obrigatório',
                'fone.regex' => 'telefone deve ter 10 ou 11 dígitos (DD + número).',
                'date.date' => ' data deve ser uma data válida.',
                'time.required' => ' horário é obrigatório.',
                'email.required' => ' email é obrigatório.',
                'email.email' => 'Insira um email válido.',
            ];

            // Vincular todos os agendamentos com o mesmo email ao usuário
            // Appointment::where('email', strtolower($request->email))
            // ->whereNull('user_id')
            // ->update(['user_id' => $user->id]);



            $fone = preg_replace('/[^0-9]/', '', $request->input('fone', ''));
            $time = $request->input('time', '');

            $request->merge([
                'fone' => $fone,
                'time' => $time,
            ]);

            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email',
                'fone' => ['required', 'regex:/^\d{10,11}$/'],
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
            ], $messages);

            // Verificar se o email existe no modelo User
            $user = User::where('email', strtolower($request->email))->first();

            $appointment = Appointment::create([
                'name' => strtolower($request->name),
                'email' => strtolower($request->email),
                'fone' => $fone,
                'date' => $request->date,
                'time' => $time,
                'user_id' => $user ? $user->id : null,
            ]);

            return redirect()->route('home')->with('success', 'Consulta marcada!');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator->errors())
                ->withInput();
        } catch (\Exception $e) {
            Log::error('Erro ao criar agendamento: ' . $e->getMessage() . ' - ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Erro ao criar agendamento: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $appointment = Appointment::findOrFail($id);

            $messages = [
                'name.required' => 'O nome é obrigatório.',
                'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
                'name.max' => 'O nome deve ter no máximo 255 caracteres.',
                'date.required' => 'A data é obrigatória.',
                'fone.regex' => 'telefone deve ter 10 ou 11 dígitos (DD + número).',
                'date.date' => 'A data deve ser uma data válida.',
                'time.required' => 'O horário é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'Insira um email válido.',
            ];


            $fone = preg_replace('/[^0-9]/', '', $request->input('fone', ''));
            $time = $request->input('time', '');

            $request->merge([
                'fone' => $fone,
                'time' => $time,
            ]);

            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email',
                'fone' => ['required', 'regex:/^\d{10,11}$/'],
                'date' => 'required|date|after_or_equal:today',
                'time' => 'required|date_format:H:i',
            ], $messages);

            $appointment->update([
                'name' => strtolower($request->name),
                'email' => strtolower($request->email),
                'fone' => $request->fone,
                'date' => $request->date,
                'time' => $request->time,
                'user_id' => $appointment->user_id,
            ]);

            return redirect()->back()->with('success', 'Consulta atualizada com sucesso!');
        } catch (\Illuminate\Validation\ValidationException $e) {
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
        $appointment->delete();

        return redirect()->route('home')->with('success', 'Consulta excluída com sucesso!');
    }

    private function getAvailableTimesForToday()
    {
        $date = now()->toDateString();
        return AppointmentService::generateAvailableTimes($date);
    }

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

    //api
    public function getReservationsOfDay(Request $request)
    {
        try {
            $date = $request->query('date');
            $reserves = Appointment::where('date', $date)->get();

            $reservations = $reserves->map(function ($reserved) {
                return [
                    'id' => $reserved->id,
                    'name' => $reserved->name,
                    'time' => Carbon::parse($reserved->time)->format('H:i'),

                ];
            });

            return response()->json(
                [
                    'reserved' => $reservations,
                    'quantity' => $reserves->count()
                ],
                200
            );
        } catch (\Exception $e) {
            return response()->json(["message"  => $e->getMessage()], 500);
        }
    }

    public function getAllAppointment()
    {
        $appointments = Appointment::all();
        return response()->json($appointments, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Services\AppointmentService;
use Error;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{


    public function index()
    {
        $appointments = Appointment::all();
        return response()->json($appointments, 200);
    }


    public function show($id)
    {
        $appointment = Appointment::findOrFail($id);
        return response()->json($appointment, 200);
    }


    public function store(Request $request)
    {
        try {
            $messages = [
                'name.required' => 'O nome é obrigatório.',
                'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
                'name.max' => 'O nome deve ter no máximo 255 caracteres.',
                'date.required' => 'A data é obrigatória.',
                'fone.min' => 'O telefone deve ter pelo menos 13 caracteres.',
                'fone.max' => 'O telefone deve ter no máximo 15 caracteres.',
                'date.date' => 'A data deve ser uma data válida.',
                'time.required' => 'O horário é obrigatório.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'Insira um email válido.',
            ];

            $request->validate([
                'name' => 'required|string|min:2|max:255',
                'email' => 'required|email',
                'fone' => 'string|min:13|max:15',
                'date' => 'required|date',
                'time' => 'required',
            ], $messages);

            $appointment = Appointment::create([
                'name' => strtolower($request->name),
                'email' => strtolower($request->email),
                'fone' => $request->fone,
                'date' => $request->date,
                'time' => $request->time,
                'user_id' => null,
            ]);
            return response()->json(['message' => 'Consulta criada!', 'Appointment' => $appointment], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $messages = [
            'name.required' => 'O nome é obrigatório.',
            'name.min' => 'O nome deve ter pelo menos 2 caracteres.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',
            'date.required' => 'A data é obrigatória.',
            'fone.min' => 'O telefone deve ter pelo menos 9 caracteres.',
            'fone.max' => 'O telefone deve ter pelo menos 11 caracteres.',
            'date.date' => 'A data deve ser uma data válida.',
            'time.required' => 'O horário é obrigatório.',
            'email.required' => 'O email é obrigatório.',
            'email.email' => 'Insira um email válido.',
        ];


        $request->validate([
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email',
            'fone' => 'string|min:9|max:11',
            'date' => 'required|date',
            'time' => 'required',
        ], $messages);

        $appointment->update([
            'name' => strtolower($request->name),
            'email' => strtolower($request->email),
            'fone' => $request->fone,
            'date' => $request->date,
            'time' => $request->time,
            'user_id' => $appointment->user_id,
        ]);

        return response()->json(['message' => 'Consulta atualizada com Sucesso', 'appointment', $appointment], 200);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return response()->json(['message' => 'Consulta excluída!'], 200);
    }
    public function getAvailableTimes(Request $request)
    {
        try {
            $date = $request->query('date');
            $availableTimes = AppointmentService::generateAvailableTimes($date);
            return response()->json(['times' => $availableTimes], 200);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }




    public function getReservationsOfDay() {}
}

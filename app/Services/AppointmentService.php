<?php

namespace App\Services;

use App\Models\Appointment;
use Carbon\Carbon;

class AppointmentService
{
    private static function getEnvConfig()
    {
        return [
            'start_exp' => env('INICIO_EXP', '09:00'),
            'end_exp' => env('FIM_EXP', '17:00'),
            'lunch_start' => env('INTERVALO_ALMOCO_INICIO', '12:00'),
            'lunch_end' => env('INTERVALO_ALMOCO_FIM', '14:00'),
            'duration_cut' => (int) env('DURACAO_CORTE', 60),
        ];
    }
    // Função para gerar lista de horários baseados no expediente, excluindo almoço
    private static function generateHoursList(string $selectedDate): array
    {
        $config = self::getEnvConfig();
        $timetables = [];
        $currentTime = Carbon::parse("{$selectedDate} {$config['start_exp']}");
        $endOffice = Carbon::parse("{$selectedDate} {$config['end_exp']}");
        $lunchStart = Carbon::parse("{$selectedDate} {$config['lunch_start']}");
        $lunchEnd = Carbon::parse("{$selectedDate} {$config['lunch_end']}");


        while ($currentTime < $endOffice) {
            $timeFormatted = $currentTime->format('H:i');
            if (!($currentTime->between($lunchStart, $lunchEnd))) {
                $timetables[] = $timeFormatted;
            }
            $currentTime->addMinutes($config['duration_cut']);
        }

        return $timetables;
    }

    private static function getBookedTimes(string $selectedDate): array
    {
        return Appointment::whereDate('date', $selectedDate)
            ->pluck('time')
            ->map(function ($time) {
                return Carbon::parse($time)->format('H:i'); // Normaliza para "H:i"
            })
            ->toArray();
    }

    public static function generateAvailableTimes(string $selectedDate): array
    {
    
        if (!$selectedDate || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $selectedDate)) {
            throw new \InvalidArgumentException('Data inválida');
        }

        $today = Carbon::today()->toDateString();
        $isToday = $selectedDate === $today;
        $now = Carbon::now();
        $config = self::getEnvConfig();
        $endOfficeCheck = Carbon::parse("{$selectedDate} {$config['end_exp']}");


        //fim do expediente, não retornar horários
        if ($isToday && $now->gt($endOfficeCheck)) {
            return []; // Retorna array vazio, indicando que não há horários disponíveis
        }

        // if ($isToday && $now > $endOfficeCheck) {
        //     $now = Carbon::parse("{$selectedDate} {$config['start_exp']}");
        // }


        $timetables = self::generateHoursList($selectedDate);
        $bookedTimes = self::getBookedTimes($selectedDate);

        return self::filterAvailableTimes($timetables, $selectedDate, $isToday, $now, $bookedTimes);
    }

    private static function filterAvailableTimes(array $timetables, string $selectedDate, bool $isToday, Carbon $now, array $bookedTimes): array
    {
        $filtered = array_filter($timetables, function ($timeFormatted) use ($selectedDate, $isToday, $now, $bookedTimes) {
            $currentTime = Carbon::parse("{$selectedDate} {$timeFormatted}");
            $isBooked = in_array($timeFormatted, $bookedTimes);
            $isPast = $isToday && $currentTime < $now;
            return !$isBooked && !$isPast;
        });
        return array_values($filtered); 
    }


    public static function reservationOfDay(string $selectedDate): array
    {
        $appointments = Appointment::whereDate('date', $selectedDate)->get();
        $reservations = $appointments->map(function ($appointment) {
            return [
                'id' => $appointment->id,
                'name' => $appointment->name,
                'time' => $appointment->time,
            ];
        })->toArray();

        $quantityReservations = count($reservations);

        return [
            'reservations' => $reservations,
            'quantityReservations' => $quantityReservations,
        ];
    }
}

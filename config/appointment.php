<?php

return [
    'start_exp' => env('INICIO_EXP', '09:00'),
    'end_exp' => env('FIM_EXP', '19:00'),
    'lunch_start' => env('INTERVALO_ALMOCO_INICIO', '12:00'),
    'lunch_end' => env('INTERVALO_ALMOCO_FIM', '13:00'),
    'duration_cut' => (int) env('DURACAO_CORTE', 40),
];

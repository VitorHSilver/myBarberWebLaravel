<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'name',
        'date',
        'fone',
        'time',
        'email',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function getAllAppointments()
    {
        return self::all();
    }

    public static function getById($id)
    {
        return self::findOrFail($id);
    }

    public static function getReservationsByDate($date)
    {
        $reserves = self::where('date', $date)->get();
        return $reserves->map(function ($reserved) {
            return [
                'id' => $reserved->id,
                'name' => $reserved->name,
                'time' => Carbon::parse($reserved->time)->format('H:i'),
                'email' => $reserved->email,
            ];
        });
    }
}

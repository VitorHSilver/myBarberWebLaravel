<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Appointment;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'fone',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'avatar' => 'array',
        ];
    }

        /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'avatar_url',
    ];

    public function getAvatarUrlAttribute(): ?string
    {
        if ($this->avatar && isset($this->avatar['path'])) {
            return asset('storage/' . $this->avatar['path']);
        }

        return null;
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProfessional(): bool
    {
        return $this->role === 'professional';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}

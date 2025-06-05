<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $primaryKey = 'id'; // Menetapkan id sebagai primary key
    protected $keyType = 'string';
    public $incrementing = false; // Non-incrementing karena menggunakan UUID

    // Menetapkan UUID saat membuat model
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->id = (string) Str::uuid(); // Mengatur uuid dengan UUID
            $user->phone = '';
            $user->address = '';
            $user->role = 'user';
            $user->photo = '';
            $user->active = true;
        });
    }
}

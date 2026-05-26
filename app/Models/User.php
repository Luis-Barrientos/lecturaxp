<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\ReadingLog;
use App\Models\Achievement;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
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
        'total_xp',
        'current_level',
        'streak_days',
        'last_read_date',
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
            'last_read_date' => 'date',
        ];
    }

    /**
     * Un usuario puede tener muchos registros de lectura.
     */
    public function readingLogs() {
        return $this->hasMany(ReadingLog::class);
    }

    /**
     * Un usuario puede tener muchos logros.
     */
    public function achievements() {
        return $this->belongsToMany(Achievement::class, 'user_achievement')
                    ->withPivot('earned_at');
    }

    /**
     * Ahora es belongsToMany porque la relación viene en user_book
     */
    public function books() {
        return $this -> belongsToMany(Book::class)
                -> withPivot('status')
                -> withTimestamps();
    }

    /**
     * Un usuario puede tener muchas reseñas de libros.
     */
    public function reviews() {

        return $this -> hasMany(Review::class);
    }
}

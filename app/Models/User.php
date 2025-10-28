<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ Important
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // ✅ If using token authentication
use Illuminate\Database\Eloquent\Collection;

/**
 * Class User
 *
 * @property int $id
 * @property string $name
 * @property int|null $age
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @property Collection|Attendance[] $attendances
 * @property Collection|Place[] $places
 */
class User extends Authenticatable  // ✅ MUST extend Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $casts = [
        'age' => 'int',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'age',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function places()
    {
        return $this->hasMany(Place::class);
    }
}

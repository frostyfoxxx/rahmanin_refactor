<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'phone_number',
        'email',
        'password',
        'stuff',
        'data_confirmed'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Связь с данными о школе
     * @return HasOne
     */
    public function schoolData(): HasOne
    {
      return $this->hasOne(School::class);
    }

    /**
     * Связь с персональными данными
     * @return HasOne
     */
    public function personalData(): HasOne
    {
      return $this->hasOne(PersonalsData::class, 'users_id', 'id');
    }

    /**
     * Связь с данными паспорта
     * @return HasOne
     */
    public function passportData(): HasOne
    {
      return $this->hasOne(Passport::class);
    }

}

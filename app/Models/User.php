<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\Requests\SignUpRequest;
use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Symfony\Component\HttpFoundation\Response;

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
        return $this->hasOne(PersonalsData::class);
    }

    /**
     * Связь с данными паспорта
     * @return HasOne
     */
    public function passportData(): HasOne
    {
        return $this->hasOne(Passport::class);
    }

    public function parents(): HasMany
    {
        return $this->hasMany(Parents::class);
    }

    /**
     * Метод регистрации пользователя
     * @param SignUpRequest $request
     * @return void
     */
    public function createUser(SignUpRequest $request)
    {
        try {
            $user = $this->create([
                    'phone_number' => $request->input('phone_number'),
                    'email' => $request->input('email'),
                    'password' => Hash::make($request->input('password')),
                    'stuff' => false
                ]
            );
            $user->roles()->attach(Roles::where('slug', 'student')->first());
            $user->save();
        } catch (ApiException $exception) {
            throw new ApiException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Registration error'
            );
        }
    }

}

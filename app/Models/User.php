<?php

namespace App\Models;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

    /*
      Блок инициализации связей для EloquentORM
    */

    public function school()
    {
      return $this->hasOne(School::class);
    }

    public function personalData()
    {
      return $this->hasOne(PersonalData::class, 'user_id', 'id');
    }

    public function passport()
    {
      return $this->hasOne(Passport::class);
    }

    /* 
      Функциональный блок модели
    */
    
    public static function checkCreateUser($request)
    {
        if ($request->email === null) {
            return Auth::attempt($request->all());
        } else {
            return User::query()->where('phone_number', '=', $request->input('phone_number'))->orWhere('email', '=' . $request->input('email'))->first();
        }
    }

    public static function createUser($request)
    {
        $user = User::create([
            'phone_number' => $request->input('phone_number'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'stuff' => false
        ]);

        $user->roles()->attach(Roles::where('slug', 'student')->first());
        $user->save();

        return true;
    }

    public static function loggedUser() {
        $user = Auth::user();
        $role = auth('sanctum')->user()->roles[0]->slug;
        $token = $user->createToken('token')->plainTextToken;
        $cookie = cookie('jwt', $token, 60 * 24 * 7); // 7 day
        
        return [
            'role' => $role,
            'cookie' => $cookie
        ];
    }
}

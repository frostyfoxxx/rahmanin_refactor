<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'series',
        'number',
        'date_of_issue',
        'issued_by',
        'date_of_birth',
        'gender',
        'place_of_birth',
        'registration_address',
        'lack_of_citizenship',
        'users_id'
    ];

    public function passport()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }
}

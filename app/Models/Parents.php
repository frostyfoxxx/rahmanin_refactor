<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname', 'name', 'patronymic', 'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function checkExistsManyParents()
    {
        $user = auth('sanctum')->user()->id;

        if(count(Parent::where('users_id', $user)->get()) === 1 ) {
            return true;
        } else {
            return false;
        }
    }
}

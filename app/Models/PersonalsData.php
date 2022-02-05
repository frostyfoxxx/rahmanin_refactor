<?php

namespace App\Models;

use App\Models\PersonalsData as ModelsPersonalsData;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalsData extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'orphan',
        'phone',
        'childhood_disabled',
        'the_large_family',
        'hostel_for_students',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,'users_id');
    }

}

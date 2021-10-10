<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_name',
        'number_of_classes',
        'year_of_ending',
        'number_of_certificate',
        'number_of_photo',
        'version_of_the_certificate',
        'middlemark',
        'users_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

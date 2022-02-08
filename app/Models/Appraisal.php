<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appraisal extends Model
{
    use HasFactory;
    /*
      Поля таблиц
    */
    protected $fillable = [
        'subject',
        'appraisal',
        'users_id'
    ];

    /*
      Связи для данной сущности EloquentORM
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

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

    public static function checkSubjectOnUser(array $data)
    {
        $user = auth('sanctum')->user()->id;

        foreach ($data as $subject) {

            if (Appraisal::where('subject', '=', $subject['subject'])->where('users_id', '=', $user)->first()) {
                return true;
            }
        }

        return false;
    }

    public static function addAppraisal(array $data)
    {
        $user = auth('sanctum')->user()->id;
        foreach ($data as $subject) {
            Appraisal::create([
                'subject' => $subject['subject'],
                'appraisal' => $subject['appraisal'],
                'users_id' => $user
            ]);
        }
    }

    public static function changeMiddlemark()
    {
        $user = auth('sanctum')->user()->id;
        $appraisal = Appraisal::select('appraisal')->where('users_id', $user)->get();
        $summark = 0;
        foreach ($appraisal as $mark) {
            $summark += $mark->appraisal;
        }
        $middlemark = round($summark / count($appraisal), 2);

        $schoolData = School::where('users_id', $user)->first();
        $schoolData['middlemark'] = $middlemark;
        $schoolData->save();
    }

    public static function getMiddlemark() {
        $user = auth('sanctum')->user()->id;
        return School::select('middlemark')->where('users_id', $user)->first()->middlemark;
    }
}

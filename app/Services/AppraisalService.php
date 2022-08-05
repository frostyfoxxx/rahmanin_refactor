<?php

namespace App\Services;

use App\Models\Appraisal;
use App\Models\School;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class AppraisalService
{
    /**
     * Проверка на существования предметов в базе
     * @return bool
     */
    public function checkAppraisalData(): bool
    {
        $user = auth('sanctum')->user()->id;
        $appraisal = Appraisal::query()->where('user_id')->get();
        if ($appraisal->isEmpty()) {
            $result = false;
        } else {
            $result = true;
        }

        return $result;
    }

    /**
     * Получение предметов пользователя
     * @return Builder[]|Collection
     */
    public function getAppraisal() {
        $user = auth('sanctum')->user()->id;

        return Appraisal::query()->where('user_id', $user)->get();
    }

    /**
     * @param Request $request - данные
     * @return void
     */
    public function addAppraisal(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        foreach ($request->all() as $subject) {
            Appraisal::create([
                'subject' => $subject['subject'],
                'appraisal' => $subject['appraisal'],
                'user_id' => $user
            ]);
        }
    }

    /**
     * Проверка на совпадения введенных предметов с уже добавленными
     * @param Request $request - данные
     * @return mixed|null
     */
    public function checkSubjectOnUser(Request $request)
    {
        $user = auth('sanctum')->user()->id;
        foreach ($request->all() as $subject) {
            if (Appraisal::query()
                ->where('user_id', $user)
                ->where('subject', $subject['subject'])
                ->first()
            ) {
                return $subject['subject'];
            }
        }

        return null;
    }

    /**
     * Полчемуение среднего балла пользователя
     * @return mixed
     */
    public function getMiddlemark()
    {
        $user = auth('sanctum')->user()->id;

        return School::select('middlemark')->where('user_id', $user)->first()->middlemark;
    }

    /**
     * Изменение среднего балла при добавлении новых предметов
     * @return void
     */
    public function changeMiddlemark()
    {
        $user = auth('sanctum')->user()->id;
        $appraisal = Appraisal::select('appraisal')->where('user_id', $user)->get();
        $summark = 0;
        foreach ($appraisal as $mark) {
            $summark += $mark->appraisal;
        }
        $middlemark = round($summark / count($appraisal), 2);

        $schoolData = School::query()->where('user_id', $user)->first();
        $schoolData['middlemark'] = $middlemark;
        $schoolData->save();
    }
}

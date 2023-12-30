<?php

namespace App\Models;

use App\Exceptions\ApiException;
use App\Http\Requests\PersonalDataRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * @property string $first_name - Имя
 * @property string $middle_name - Отчество
 * @property string $last_name - Фамилия
 * @property string $orphan - Сирота
 * @property numeric $phone - Номер телефона
 * @property string $childhood_disabled - Инвалид детства
 * @property string $the_large_family - Многодетная семья
 * @property string $hostel_for_students - Нуждается в общежитии
 * @property int $user_id Идентификатор пользователя, которому принадлежат данные
 * @property-read $user
 */
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
        'user_id'
    ];

    /**
     * Связь с пользователем
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Создание персональных данных
     * @param PersonalDataRequest $request -Объект с персональными данными
     * @return void
     */
    public function createPersonalData(PersonalDataRequest $request): void
    {
        try {
            $pData = $this->make($request->all());
            $pData->user_id = Auth::user()->id;
            $pData->save();
        } catch (ApiException $exc) {
            throw new ApiException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Ошибка создания данных'
            );
        }
    }

    /**
     * Обновление персональных данных пользователя
     * @param PersonalsData $pData - Объект с найденными данными пользователя
     * @param PersonalDataRequest $request - Объект с новыми данными
     * @return void
     */
    public function updatePersonalData(PersonalsData $pData, PersonalDataRequest $request): void
    {
        try {
            $pData->first_name = $request->first_name;
            $pData->middle_name = $request->middle_name;
            $pData->last_name = $request->last_name;
            $pData->phone = $request->phone;
            $pData->orphan = $request->orphan;
            $pData->childhood_disabled = $request->childhood_disabled;
            $pData->the_large_family = $request->the_large_family;
            $pData->hostel_for_students = $request->hostel_for_students;
            $pData->save();
        } catch (ApiException $exc) {
            throw new ApiException(
                Response::HTTP_UNPROCESSABLE_ENTITY,
                'Ошибка обновления данных'
            );
        }
    }
}

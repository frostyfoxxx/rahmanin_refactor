<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(User::class,'user_id');
    }

}

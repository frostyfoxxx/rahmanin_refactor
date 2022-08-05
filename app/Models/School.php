<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель сущности schools
 * @property string $school_name Название школы
 * @property int $number_of_classes Количество оконченных классов
 * @property int $year_of_ending Год окончания школы
 * @property string $number_of_certificate Номер аттестата
 * @property int $number_of_photo Количество фотографий, предъявленных при очной встрече
 * @property string $version_of_certificate Версия сертификата (копия или оригинал)
 * @property double $middlemark Средний балл
 * @property int $user_id Ключ-ссылка на пользователя
 * @property-read $users Связь с сущностью Пользователь
 */
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
        'user_id'
    ];

    /**
     * Связь с сущностью Users
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель сущности passports
 * @property int $series Серия паспорта
 * @property int $number Номер паспорта
 * @property string $date_of_issue Дата выдачи паспорта
 * @property string $issued_by Кем выдан паспорт
 * @property string $date_of_birth Дата рождения по паспорту
 * @property string $gender Пол по паспорту
 * @property string $place_of_birth Место рождения
 * @property string $registration_address Адрес регистрации
 * @property bool lack_of_citizenship Нет гражданства РФ
 */
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
        'user_id'
    ];

    /**
     * Связь с пользователем
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

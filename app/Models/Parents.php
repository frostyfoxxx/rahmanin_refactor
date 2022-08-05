<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Модель=сущность данных о родителях
 * @property string $surname Фамилия
 * @property string $name Имя
 * @property string $patronymic Отчество
 * @property string $phone Контактный номер телефона
 * @author frostyfoxx
 */
class Parents extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname', 'name', 'patronymic', 'phone', 'users_ud'
    ];

    /**
     * Связь с сущностью пользователя
     * @return BelongsTo
     */
    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

<?php

use App\Http\Controllers\AppraisalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\PassportController;
use App\Http\Controllers\PersonalDataController;
use App\Http\Controllers\SchoolController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/signup', [AuthController::class, 'signUp']); // Регистрация
Route::post('/auth', [AuthController::class, 'signIn']); // Аутентификация

Route::group(['middleware' => ['auth:sanctum']], function () { // Методы аутентифицированного пользователя
    Route::post('/logout', [AuthController::class, 'logout']); // Выход
    Route::get('/user/personal', [PersonalDataController::class, 'getPersonalData']); // Получение персональных данных
    Route::post('/user/personal', [PersonalDataController::class, 'postPersonalData']); // Добавление персональных данных
    Route::patch('/user/personal', [PersonalDataController::class, 'patchPersonalData']); // Обновление персональных данных
    Route::get('user/passport', [PassportController::class, 'getPassportData']); // Получение паспортных данных
    Route::post('/user/passport', [PassportController::class, 'createPassportData']); // Создание паспортных данных
    Route::patch('/user/passport', [PassportController::class, 'updatePassportData']); // Обновление паспортных данных
    Route::get('user/school', [SchoolController::class, 'getSchool']); // Получение данных о школьном образовании
    Route::post('/user/school', [SchoolController::class, 'createSchoolData']); // Добавление данных о школьном образовании
    Route::patch('/user/school', [SchoolController::class, 'updateSchoolData']); // Обновление данных о школьном образовании
    Route::get('/user/stuff', [AppraisalController::class, 'getUserAppraisal']); // Получение данных о предметах аттестата и оценок по ним
    Route::post('user/stuff', [AppraisalController::class, 'createUserAppraisal']); // Добавление данных о предметах аттестата и оценок по ним
    Route::post('/user/parents', [ParentController::class, 'createParent']); // Добавление родителей
});



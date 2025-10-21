<?php

use App\Http\Controllers\Api\PartnerApiController;
use App\Http\Controllers\Api\PublicApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Публичные API маршруты
Route::prefix('public')->group(function () {
    // События
    Route::get('/events', [PublicApiController::class, 'getEvents']);
    Route::get('/events/{id}', [PublicApiController::class, 'getEvent']);

    // Новости
    Route::get('/news', [PublicApiController::class, 'getNews']);
    Route::get('/news/{id}', [PublicApiController::class, 'getNewsItem']);

    // Гранты
    Route::get('/grants', [PublicApiController::class, 'getGrants']);
    Route::get('/grants/{id}', [PublicApiController::class, 'getGrant']);

    // Вакансии
    Route::get('/vacancies', [PublicApiController::class, 'getVacancies']);
    Route::get('/vacancies/{id}', [PublicApiController::class, 'getVacancy']);
});

// API для партнёрского приложения
Route::prefix('partner')->group(function () {
    // Авторизация (без middleware)
    Route::post('/login', [PartnerApiController::class, 'login']);

    // Защищённые маршруты (требуют авторизации)
    Route::middleware('api.token.auth')->group(function () {
        // Информация о пользователе по ID
        Route::get('/user/{user_id}', [PartnerApiController::class, 'getUserById']);

        // Списание баллов
        Route::post('/deduct-points', [PartnerApiController::class, 'deductPoints']);

        // История транзакций
        Route::get('/transactions', [PartnerApiController::class, 'getTransactionHistory']);

        // Выход из системы
        Route::post('/logout', [PartnerApiController::class, 'logout']);
    });
});

<?php

use App\Http\Controllers\Api\PartnerApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

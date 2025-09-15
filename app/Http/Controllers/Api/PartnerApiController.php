<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Points;
use App\Models\ApiToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PartnerApiController extends Controller
{
    /**
     * Авторизация партнёра
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('email', $request->email)
                   ->where('role', 'partner')
                   ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Неверный email или пароль'
            ], 401);
        }

        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Аккаунт заблокирован'
            ], 403);
        }

        // Создаём токен для API
        $apiToken = ApiToken::createForUser($user, 'partner-app');

        return response()->json([
            'success' => true,
            'message' => 'Успешная авторизация',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->getFullName(),
                    'profile' => $user->partnersProfile
                ],
                'token' => $apiToken->token,
                'expires_at' => $apiToken->expires_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Получение информации о пользователе по ID из QR-кода
     */
    public function getUserById(Request $request, $userId)
    {
        $validator = Validator::make(['user_id' => $userId], [
            'user_id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не найден'
            ], 404);
        }

        $user = User::with('youthProfile')->find($userId);

        if (!$user || $user->role !== 'youth') {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не найден или не является участником молодёжной программы'
            ], 404);
        }

        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Аккаунт пользователя заблокирован'
            ], 403);
        }

        // Подсчитываем баланс баллов пользователя
        $totalEarned = Points::where('user_id', $user->id)->where('points', '>', 0)->sum('points');
        $totalSpent = abs(Points::where('user_id', $user->id)->where('points', '<', 0)->sum('points'));
        $currentBalance = $totalEarned - $totalSpent;

        return response()->json([
            'success' => true,
            'message' => 'Информация о пользователе получена',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'user_id' => $user->getUserId(),
                    'name' => $user->getFullName(),
                    'email' => $user->email,
                    'profile' => $user->youthProfile,
                    'points_balance' => $currentBalance,
                    'total_earned' => $totalEarned,
                    'total_spent' => $totalSpent
                ]
            ]
        ]);
    }

    /**
     * Списание баллов у пользователя
     */
    public function deductPoints(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'points' => 'required|integer|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors()
            ], 422);
        }

        $partner = $request->api_user;
        $user = User::find($request->user_id);

        if (!$user || $user->role !== 'youth') {
            return response()->json([
                'success' => false,
                'message' => 'Пользователь не найден или не является участником молодёжной программы'
            ], 404);
        }

        if ($user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Аккаунт пользователя заблокирован'
            ], 403);
        }

        // Проверяем баланс баллов пользователя
        $totalEarned = Points::where('user_id', $user->id)->where('points', '>', 0)->sum('points');
        $totalSpent = abs(Points::where('user_id', $user->id)->where('points', '<', 0)->sum('points'));
        $currentBalance = $totalEarned - $totalSpent;

        if ($currentBalance < $request->points) {
            return response()->json([
                'success' => false,
                'message' => 'Недостаточно баллов для списания',
                'data' => [
                    'current_balance' => $currentBalance,
                    'requested_points' => $request->points
                ]
            ], 400);
        }

        // Создаём запись о списании баллов
        $pointsRecord = Points::create([
            'user_id' => $user->id,
            'partner_id' => $partner->id,
            'points' => -$request->points, // Отрицательное значение для списания
            'extra' => $request->description ?: ('Списание баллов у партнёра: ' . $partner->getFullName())
        ]);

        // Пересчитываем баланс после списания
        $newBalance = $currentBalance - $request->points;

        return response()->json([
            'success' => true,
            'message' => 'Баллы успешно списаны',
            'data' => [
                'transaction_id' => $pointsRecord->id,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->getFullName(),
                    'previous_balance' => $currentBalance,
                    'deducted_points' => $request->points,
                    'new_balance' => $newBalance
                ],
                'partner' => [
                    'id' => $partner->id,
                    'name' => $partner->getFullName()
                ],
                'description' => $pointsRecord->extra,
                'created_at' => $pointsRecord->created_at->format('Y-m-d H:i:s')
            ]
        ]);
    }

    /**
     * Получение истории транзакций партнёра
     */
    public function getTransactionHistory(Request $request)
    {
        $partner = $request->api_user;

        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка валидации',
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Points::where('partner_id', $partner->id)
                      ->with(['user.youthProfile'])
                      ->orderBy('created_at', 'desc');

        // Фильтр по датам
        if ($request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $perPage = $request->per_page ?: 20;
        $transactions = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'История транзакций получена',
            'data' => [
                'transactions' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total()
                ]
            ]
        ]);
    }

    /**
     * Выход из системы
     */
    public function logout(Request $request)
    {
        $token = $request->bearerToken();

        if ($token) {
            ApiToken::where('token', $token)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Успешный выход из системы'
        ]);
    }
}

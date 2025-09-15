<?php

namespace App\Http\Middleware;

use App\Models\ApiToken;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiTokenAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Токен авторизации не предоставлен'
            ], 401);
        }

        $apiToken = ApiToken::with('user')->where('token', $token)->first();

        if (!$apiToken) {
            return response()->json([
                'success' => false,
                'message' => 'Недействительный токен'
            ], 401);
        }

        if (!$apiToken->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Срок действия токена истёк'
            ], 401);
        }

        if ($apiToken->user->is_blocked) {
            return response()->json([
                'success' => false,
                'message' => 'Аккаунт заблокирован'
            ], 403);
        }

        // Обновляем время последнего использования
        $apiToken->updateLastUsed();

        // Добавляем пользователя в запрос
        $request->merge(['api_user' => $apiToken->user]);

        return $next($request);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ApiToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'name',
        'expires_at',
        'last_used_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'last_used_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Генерация токена
     */
    public static function generateToken()
    {
        return hash('sha256', Str::random(40));
    }

    /**
     * Создание нового токена для пользователя
     */
    public static function createForUser(User $user, string $name, int $expiresInDays = 30)
    {
        // Удаляем старые токены пользователя
        self::where('user_id', $user->id)->delete();

        $token = self::generateToken();

        return self::create([
            'user_id' => $user->id,
            'token' => $token,
            'name' => $name,
            'expires_at' => Carbon::now()->addDays($expiresInDays),
        ]);
    }

    /**
     * Проверка валидности токена
     */
    public function isValid()
    {
        return $this->expires_at === null || $this->expires_at->isFuture();
    }

    /**
     * Обновление времени последнего использования
     */
    public function updateLastUsed()
    {
        $this->update(['last_used_at' => Carbon::now()]);
    }
}

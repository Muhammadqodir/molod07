<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Like;
use App\Models\View;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Auth;

trait HasInteractions
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(Like::class, 'likeable');
    }

    public function views(): MorphMany
    {
        return $this->morphMany(View::class, 'viewable');
    }

    public function getCommentsCountAttribute()
    {
        return $this->comments()->parents()->count();
    }

    public function getLikesCountAttribute()
    {
        return $this->likes()->likes()->count();
    }

    public function getDislikesCountAttribute()
    {
        return $this->likes()->dislikes()->count();
    }

    public function getViewsCountAttribute()
    {
        return $this->views()->count();
    }

    public function hasUserLiked($userId = null)
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return false;

        return $this->likes()->where('user_id', $userId)->where('type', 'like')->exists();
    }

    public function hasUserDisliked($userId = null)
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return false;

        return $this->likes()->where('user_id', $userId)->where('type', 'dislike')->exists();
    }

    public function toggleLike($userId = null, $type = 'like')
    {
        $userId = $userId ?? Auth::id();
        if (!$userId) return false;

        $existingLike = $this->likes()->where('user_id', $userId)->first();

        if ($existingLike) {
            if ($existingLike->type === $type) {
                // Если уже есть такой же тип лайка - удаляем
                $existingLike->delete();
                return 'removed';
            } else {
                // Если есть противоположный тип - меняем
                $existingLike->update(['type' => $type]);
                return 'changed';
            }
        } else {
            // Создаем новый лайк
            $this->likes()->create([
                'user_id' => $userId,
                'type' => $type,
            ]);
            return 'added';
        }
    }

    public function addView($userId = null, $ipAddress = null, $userAgent = null)
    {
        $ipAddress = $ipAddress ?? request()->ip();
        $userAgent = $userAgent ?? request()->header('User-Agent');

        // Проверяем, есть ли уже просмотр от этого пользователя/IP за последние 24 часа
        $query = $this->views()->where('created_at', '>', now()->subDay());

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('ip_address', $ipAddress);
        }

        if (!$query->exists()) {
            return $this->views()->create([
                'user_id' => $userId,
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ]);
        }

        return null;
    }
}

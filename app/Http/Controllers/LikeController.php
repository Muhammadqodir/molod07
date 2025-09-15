<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        $request->validate([
            'likeable_type' => 'required|string',
            'likeable_id' => 'required|integer',
            'type' => 'required|in:like,dislike',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Необходимо войти в систему'], 401);
        }

        $modelClass = $this->getModelClass($request->input('likeable_type'));
        if (!$modelClass) {
            return response()->json(['error' => 'Неверный тип объекта'], 400);
        }

        $model = $modelClass::find($request->input('likeable_id'));
        if (!$model) {
            return response()->json(['error' => 'Объект не найден'], 404);
        }

        $result = $model->toggleLike(Auth::id(), $request->input('type'));

        return response()->json([
            'success' => true,
            'action' => $result,
            'likes_count' => $model->likes_count,
            'dislikes_count' => $model->dislikes_count,
            'user_liked' => $model->hasUserLiked(),
            'user_disliked' => $model->hasUserDisliked(),
        ]);
    }

    private function getModelClass($type)
    {
        $models = [
            'App\\Models\\News' => \App\Models\News::class,
            'App\\Models\\Event' => \App\Models\Event::class,
            'App\\Models\\Grant' => \App\Models\Grant::class,
            'App\\Models\\Course' => \App\Models\Course::class,
            'App\\Models\\Podcast' => \App\Models\Podcast::class,
            'App\\Models\\Vacancy' => \App\Models\Vacancy::class,
            'App\\Models\\Comment' => \App\Models\Comment::class,
        ];

        return $models[$type] ?? null;
    }
}

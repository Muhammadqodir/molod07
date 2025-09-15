<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewController extends Controller
{
    public function track(Request $request)
    {
        $request->validate([
            'viewable_type' => 'required|string',
            'viewable_id' => 'required|integer',
        ]);

        $modelClass = $this->getModelClass($request->input('viewable_type'));
        if (!$modelClass) {
            return response()->json(['error' => 'Неверный тип объекта'], 400);
        }

        $model = $modelClass::find($request->input('viewable_id'));
        if (!$model) {
            return response()->json(['error' => 'Объект не найден'], 404);
        }

        $view = $model->addView(
            Auth::id(),
            $request->ip(),
            $request->header('User-Agent')
        );

        return response()->json([
            'success' => true,
            'view_added' => $view !== null,
            'views_count' => $model->views_count,
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
        ];

        return $models[$type] ?? null;
    }
}

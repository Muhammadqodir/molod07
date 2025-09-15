<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\Points;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PointsController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Получаем все записи баллов для текущего пользователя
        $points = Points::where('user_id', $user->id)
            ->with(['event', 'partner'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Группируем баллы на заработанные (положительные) и потраченные (отрицательные)
        $earnedPoints = $points->where('points', '>', 0);
        $spentPoints = $points->where('points', '<', 0);

        // Считаем общий баланс
        $totalEarned = $earnedPoints->sum('points');
        $totalSpent = abs($spentPoints->sum('points'));
        $currentBalance = $totalEarned - $totalSpent;

        return view('youth.points.index', compact(
            'points',
            'earnedPoints',
            'spentPoints',
            'totalEarned',
            'totalSpent',
            'currentBalance'
        ));
    }
}

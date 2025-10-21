<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:1000',
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
            'parent_id' => 'nullable|integer|exists:comments,id',
        ]);

        if (!Auth::check()) {
            return response()->json(['error' => 'Необходимо войти в систему'], 401);
        }

        // Проверяем на дублирование комментариев за последние 10 секунд
        $recentComment = Comment::where('user_id', Auth::id())
            ->where('content', $request->input('content'))
            ->where('commentable_type', $request->input('commentable_type'))
            ->where('commentable_id', $request->input('commentable_id'))
            ->where('created_at', '>', now()->subSeconds(10))
            ->first();

        if ($recentComment) {
            return response()->json([
                'success' => false,
                'message' => 'Комментарий уже был добавлен'
            ], 422);
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'commentable_type' => $request->input('commentable_type'),
            'commentable_id' => $request->input('commentable_id'),
            'parent_id' => $request->input('parent_id'),
            'status' => 'approved', // Сразу одобряем комментарий
        ]);

        $comment->load('user');

        // Генерируем HTML для нового комментария
        $html = view('components.comment-item', [
            'commentId' => $comment->id,
            'author' => $comment->user ? $comment->user->getFullName() : 'Аноним',
            'time' => $comment->created_at->diffForHumans(),
            'body' => $comment->content,
            'likes' => 0,
            'dislikes' => 0,
            'commentableType' => $request->input('commentable_type'),
            'commentableId' => $request->input('commentable_id'),
            'isReply' => !is_null($request->input('parent_id')),
        ])->render();

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'html' => $html,
            'message' => 'Комментарий добавлен успешно'
        ]);
    }

    public function destroy(Comment $comment)
    {
        if (!Auth::check() || Auth::id() !== $comment->user_id) {
            return response()->json(['error' => 'Недостаточно прав'], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Комментарий удален'
        ]);
    }

    public function index(Request $request)
    {
        $request->validate([
            'commentable_type' => 'required|string',
            'commentable_id' => 'required|integer',
        ]);

        $comments = Comment::where('commentable_type', $request->input('commentable_type'))
            ->where('commentable_id', $request->input('commentable_id'))
            ->where('status', 'approved') // Показываем только одобренные комментарии
            ->whereNull('parent_id')
            ->with(['user', 'replies' => function($query) {
                $query->where('status', 'approved'); // Также фильтруем ответы
            }, 'replies.user'])
            ->latest()
            ->paginate(10);

        // Если запрос AJAX, возвращаем HTML
        if ($request->ajax()) {
            $html = '';
            foreach ($comments as $comment) {
                $html .= view('components.comment-item', [
                    'commentId' => $comment->id,
                    'author' => $comment->user ? $comment->user->getFullName() : 'Аноним',
                    'time' => $comment->created_at->diffForHumans(),
                    'body' => $comment->content,
                    'likes' => $comment->likes()->likes()->count(),
                    'dislikes' => $comment->likes()->dislikes()->count(),
                    'commentableType' => $request->input('commentable_type'),
                    'commentableId' => $request->input('commentable_id'),
                    'isReply' => false,
                ])->render();

                // Добавляем ответы
                foreach ($comment->replies as $reply) {
                    $html .= view('components.comment-item', [
                        'commentId' => $reply->id,
                        'author' => $reply->user ? $reply->user->getFullName() : 'Аноним',
                        'time' => $reply->created_at->diffForHumans(),
                        'body' => $reply->content,
                        'likes' => $reply->likes()->likes()->count(),
                        'dislikes' => $reply->likes()->dislikes()->count(),
                        'commentableType' => $request->input('commentable_type'),
                        'commentableId' => $request->input('commentable_id'),
                        'isReply' => true,
                    ])->render();
                }
            }

            return response()->json([
                'success' => true,
                'html' => $html,
                'hasMore' => $comments->hasMorePages()
            ]);
        }

        return response()->json($comments);
    }
}

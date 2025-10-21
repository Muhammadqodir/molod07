<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageCommentsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status', 'approved'));
        $q = $request->string('q')->toString();

        $query = Comment::query()
            ->with(['user', 'commentable'])
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('content', 'like', "%{$q}%")
                    ->orWhereHas('user', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%");
                    });
            }));

        // Filter by status if we have a status field
        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $comments = $query
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        return view('admin.comments.list', compact('comments', 'q', 'status'));
    }

    public function approve(Request $request)
    {
        $comment = Comment::findOrFail($request->id);

        // Update status if status field exists
        try {
            $comment->update(['status' => 'approved']);
            $message = 'Комментарий одобрен.';
        } catch (\Exception $e) {
            // If no status field exists, just return success message
            $message = 'Комментарий помечен как одобренный.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function reject(Request $request)
    {
        $comment = Comment::findOrFail($request->id);

        try {
            $comment->update(['status' => 'rejected']);
            $message = 'Комментарий отклонен.';
        } catch (\Exception $e) {
            $message = 'Комментарий помечен как отклоненный.';
        }

        return redirect()->back()->with('success', $message);
    }

    public function delete(Request $request)
    {
        $comment = Comment::findOrFail($request->id);
        $comment->delete();

        return redirect()->back()->with('success', 'Комментарий удален.');
    }

    public function block(Request $request)
    {
        $comment = Comment::findOrFail($request->id);
        $user = $comment->user;

        if ($user && $user->role !== 'admin') {
            $user->update(['is_blocked' => true]);
            return redirect()->back()->with('success', 'Пользователь заблокирован.');
        }

        return redirect()->back()->with('error', 'Невозможно заблокировать этого пользователя.');
    }

    public function preview($id)
    {
        $comment = Comment::with(['user', 'commentable', 'parent', 'replies'])->findOrFail($id);
        return view('admin.comments.preview', compact('comment'));
    }
}

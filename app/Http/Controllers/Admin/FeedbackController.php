<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        $query = Feedback::with('user')->latest();

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by subject or message
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('subject', 'like', "%{$searchTerm}%")
                  ->orWhere('message', 'like', "%{$searchTerm}%");
            });
        }

        $feedback = $query->paginate(20);

        return view('admin.feedback.index', compact('feedback'));
    }

    public function show(Feedback $feedback)
    {
        $feedback->load('user');

        return view('admin.feedback.show', compact('feedback'));
    }

    public function updateStatus(Request $request, Feedback $feedback)
    {
        $request->validate([
            'status' => 'required|in:new,in_progress,resolved,closed'
        ]);

        $feedback->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Статус обратной связи успешно обновлен.');
    }

    public function destroy(Feedback $feedback)
    {
        // Delete screenshot if exists
        if ($feedback->screenshot) {
            Storage::disk('public')->delete($feedback->screenshot);
        }

        $feedback->delete();

        return redirect()->route('admin.feedback.index')->with('success', 'Обратная связь успешно удалена.');
    }

    public function bulkAction(Request $request)
    {
        $request->validate([
            'feedback_ids' => 'required|array',
            'feedback_ids.*' => 'exists:feedback,id',
            'action' => 'required|in:mark_resolved,mark_closed,delete'
        ]);

        $feedbackIds = $request->feedback_ids;

        switch ($request->action) {
            case 'mark_resolved':
                Feedback::whereIn('id', $feedbackIds)->update(['status' => 'resolved']);
                $message = 'Выбранные обращения отмечены как решенные.';
                break;

            case 'mark_closed':
                Feedback::whereIn('id', $feedbackIds)->update(['status' => 'closed']);
                $message = 'Выбранные обращения отмечены как закрытые.';
                break;

            case 'delete':
                $feedback = Feedback::whereIn('id', $feedbackIds)->get();
                foreach ($feedback as $item) {
                    if ($item->screenshot) {
                        Storage::disk('public')->delete($item->screenshot);
                    }
                    $item->delete();
                }
                $message = 'Выбранные обращения удалены.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }
}

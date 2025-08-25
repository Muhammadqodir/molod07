<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Models\News;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageNewsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();
        $events = News::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
            $w->where('title', 'like', "%{$q}%")
                ->orWhere('short_description', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add partner and supervisor fields to each event
        foreach ($events as $event) {
            $event->partner = User::find($event->user_id);
            $event->supervisor = User::find($event->supervisor_id);
        }

        return view('admin.news.list', compact('events', 'q'));
    }

    public function preview($id)
    {
        $event = News::findOrFail($id);
        return view('pages.news', compact('event'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(CreateNewsRequest $request)
    {
        $v = $request->validated();

        $v['user_id'] = Auth::id();

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverName = time() . '_' . uniqid() . '.' . $coverFile->getClientOriginalExtension();
            $coverFile->move(public_path('uploads/news'), $coverName);
            $v['cover'] = 'uploads/news/' . $coverName;
        }

        // Set default status if not provided
        if (!isset($v['status'])) {
            $v['status'] = 'draft';
        }

        // Create the news entry
        $news = News::create($v);

        return redirect()
            ->route('admin.news.list')
            ->with('success', 'Новость успешно создана.');
    }

    public function approve($id)
    {
        $event = News::findOrFail($id);
        $event->status = 'approved';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route('admin.news.list', $event)
            ->with('success', 'Новость успешно одобрена.');
    }

    public function reject($id)
    {
        $event = News::findOrFail($id);
        $event->status = 'rejected';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route('admin.news.list', $event)
            ->with('success', 'Новость успешно отклонена.');
    }
}

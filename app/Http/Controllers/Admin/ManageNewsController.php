<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateNewsRequest;
use App\Http\Requests\UpdateNewsRequest;
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

        $query = News::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            }));

        // If user is not admin, show only their news
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::id());
        } else if (!Auth::user() || Auth::user()->role === 'admin') {
            $query->where('status', $status);
        }

        $news = $query
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add partner and supervisor fields to each news item
        foreach ($news as $item) {
            $item->partner = User::find($item->user_id);
            $item->supervisor = User::find($item->supervisor_id);
        }

        return view('admin.news.list', compact('news', 'q'));
    }

    public function preview($id)
    {
        $news = News::findOrFail($id);
        return view('pages.news', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(CreateNewsRequest $request)
    {
        $v = $request->validated();

        $v['user_id'] = Auth::id();

        // Set publication_date to today if not provided
        if (empty($v['publication_date'])) {
            $v['publication_date'] = now()->toDateString();
        }

        // Handle cover image upload using storage
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverPath = $coverFile->store('news_covers', 'public');
            $v['cover'] = 'storage/' . $coverPath;
        }

        if (Auth::user() && Auth::user()->role === 'admin') {
            $v['status'] = 'approved';
        } else {
            $v['status'] = 'pending';
        }

        // Create the news entry
        $news = News::create($v);

        return redirect()
            ->route(Auth::user()->role . '.news.index')
            ->with('success', 'Новость успешно создана.');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);

        // Check if user can edit this news
        if (Auth::user()->role !== 'admin' && $news->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        return view('admin.news.edit', compact('news'));
    }

    public function update(UpdateNewsRequest $request, $id)
    {
        $news = News::findOrFail($id);

        // Check if user can edit this news
        if (Auth::user()->role !== 'admin' && $news->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $v = $request->validated();

        // Set publication_date to today if not provided
        if (empty($v['publication_date'])) {
            $v['publication_date'] = now()->toDateString();
        }

        // Handle cover image upload using storage if new file is provided
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($news->cover && file_exists(public_path($news->cover))) {
                unlink(public_path($news->cover));
            }

            $coverFile = $request->file('cover');
            $coverPath = $coverFile->store('news_covers', 'public');
            $v['cover'] = 'storage/' . $coverPath;
        }

        // Update the news entry
        $news->update($v);

        return redirect()
            ->route(Auth::user()->role . '.news.index')
            ->with('success', 'Новость успешно обновлена.');
    }

    public function approve($id)
    {
        $event = News::findOrFail($id);
        $event->status = 'approved';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route('admin.news.index', $event)
            ->with('success', 'Новость успешно одобрена.');
    }

    public function reject($id)
    {
        $event = News::findOrFail($id);
        $event->status = 'rejected';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route(Auth::user()->role . '.news.index', $event)
            ->with('success', 'Новость успешно отклонена.');
    }

    public function archive($id)
    {
        $news = News::findOrFail($id);
        $news->status = 'archived';
        $news->save();

        return redirect()
            ->route(Auth::user()->role . '.news.index')
            ->with('success', 'Новость успешно перемещена в архив.');
    }

    public function remove($id)
    {
        $news = News::findOrFail($id);
        $news->delete();

        return redirect()
            ->route(Auth::user()->role . '.news.index')
            ->with('success', 'Новость успешно удалена.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePodcastRequest;
use App\Models\Podcast;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManagePodcastsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();

        $podcasts = Podcast::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add user (creator) information to each podcast
        foreach ($podcasts as $podcast) {
            $podcast->creator = User::find($podcast->user_id);
            $podcast->admin = User::find($podcast->admin_id);
        }

        return view('admin.podcasts.list', compact('podcasts', 'q'));
    }

    public function create()
    {
        return view('admin.podcasts.create');
    }

    public function store(CreatePodcastRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('uploads/podcasts/covers', 'public');
            $validated['cover'] = 'storage/' . $coverPath;
        }

        // Set admin_id to current authenticated admin
        $validated['user_id'] = Auth::id();
        $validated['admin_id'] = Auth::id();
        $validated['status'] = 'approved'; // Admin-created podcasts are auto-approved

        $podcast = Podcast::create($validated);

        return redirect()->route('admin.podcasts.index')
            ->with('success', 'Подкаст успешно создан!');
    }

    public function preview($id)
    {
        $podcast = Podcast::findOrFail($id);
        $podcast->creator = User::find($podcast->user_id);
        $podcast->admin = User::find($podcast->admin_id);

        return view('pages.podcast', compact('podcast'));
    }

    public function approve($id)
    {
        $podcast = Podcast::findOrFail($id);
        $podcast->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Подкаст одобрен!');
    }

    public function reject($id)
    {
        $podcast = Podcast::findOrFail($id);
        $podcast->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Подкаст отклонен!');
    }

    public function archive($id)
    {
        $podcast = Podcast::findOrFail($id);
        $podcast->update([
            'status' => 'archived',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Подкаст архивирован!');
    }

    public function destroy($id)
    {
        $podcast = Podcast::findOrFail($id);

        // Delete cover file if exists
        if ($podcast->cover && Storage::disk('public')->exists($podcast->cover)) {
            Storage::disk('public')->delete($podcast->cover);
        }

        $podcast->delete();

        return redirect()->back()->with('success', 'Подкаст удален!');
    }
}

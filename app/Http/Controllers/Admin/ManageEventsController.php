<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageEventsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();
        $events = Event::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
            $w->where('title', 'like', "%{$q}%")
                ->orWhere('short_description', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        return view('admin.events.list', compact('events', 'q'));
    }

    public function preview($id)
    {
        $event = Event::findOrFail($id);
        return view('pages.event', compact('event'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(CreateEventRequest $request)
    {

        // dd('Store event', $request->all());
        $v = $request->validated();

        // если user_id не прислан — берём из auth
        $v['user_id'] = $v['user_id'] ?? Auth::id();

        return DB::transaction(function () use ($request, $v) {
            // 1) создаём запись без файлов
            $event = Event::create([
                'user_id'            => $v['user_id'],
                'category'           => $v['category'],
                'type'               => $v['type'],
                'title'              => $v['title'],
                'short_description'  => $v['short_description'],
                'description'        => $v['description'] ?? null,
                'address'            => $v['address'] ?? null,
                'settlement'         => $v['settlement'] ?? null,
                'start'              => $v['start'],
                'end'                => $v['end'] ?? null,
                'supervisor_id'      => $v['supervisor_id'] ?? null,
                'supervisor_name'    => $v['supervisor_name'] ?? null,
                'supervisor_l_name'  => $v['supervisor_l_name'] ?? null,
                'supervisor_phone'   => $v['supervisor_phone'] ?? null,
                'supervisor_email'   => $v['supervisor_email'] ?? null,
                'web'                => $v['web'] ?? null,
                'telegram'           => $v['telegram'] ?? null,
                'vk'                 => $v['vk'] ?? null,
                'videos'             => $v['videos'] ?? [],
                'roles'              => $v['roles'] ?? [],
                'status'             => $v['status'] ?? 'pending',
                'admin_id'           => Auth::id(),
            ]);

            // 2) файлы
            // cover (один)
            if ($request->hasFile('cover')) {
                $path = $request->file('cover')->store("uploads/events{$event->id}/cover", 'public');
                $event->update(['cover' => $path]);
            }

            // docs (массив любых файлов)
            $docPaths = [];
            if ($request->hasFile('docs')) {
                foreach ($request->file('docs') as $file) {
                    $docPaths[] = $file->store("uploads/events{$event->id}/docs", 'public');
                }
            }

            // images (массив изображений)
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imagePaths[] = $img->store("uploads/events{$event->id}/images", 'public');
                }
            }

            // videos (массив видео)
            $videoPaths = [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = $video->store("uploads/events{$event->id}/videos", 'public');
                }
            }

            if ($docPaths || $imagePaths || $videoPaths) {
                $event->update([
                    'docs'   => $docPaths ?: [],
                    'images' => $imagePaths ?: [],
                    'videos' => $videoPaths ?: [],
                ]);
            }

            return redirect()
                ->route('admin.events.index', $event)
                ->with('success', 'Событие успешно создано.');
        });
    }
}

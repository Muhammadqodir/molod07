<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Models\Event;
use App\Models\Participant;
use App\Models\Points;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageEventsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();

        $eventsQuery = Event::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            }));

        // If user is not admin, show only their news
        if (!Auth::user() || Auth::user()->role !== 'admin') {
            $eventsQuery->where('user_id', Auth::id());
        } else if (Auth::user() && Auth::user()->role === 'admin') {
            $eventsQuery->where('status', $status);
        }

        $events = $eventsQuery
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add partner and supervisor fields to each event
        foreach ($events as $event) {
            $event->partner = User::find($event->user_id);
            $event->supervisor = User::find($event->supervisor_id);
        }

        return view('admin.events.list', compact('events', 'q'));
    }

    public function preview($id)
    {
        $event = Event::findOrFail($id);
        $event->partner = User::find($event->user_id);
        $event->supervisor = User::find($event->supervisor_id);
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

        if (Auth::user() && Auth::user()->role === 'admin') {
            $v['status'] = 'approved';
            $v['user_id'] = $v['user_id'] ?? Auth::id();
        } else {
            $v['status'] = 'pending';
            $v['user_id'] = Auth::id();
        }

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
                'status'             => $v['status'] ?? 'approved',
                'admin_id'           => Auth::id(),
            ]);

            // 2) файлы
            // cover (один)
            if ($request->hasFile('cover')) {
                $path = $request->file('cover')->store("uploads/events{$event->id}/cover", 'public');
                $event->update(['cover' => 'storage/' . $path]);
            }

            // docs (массив любых файлов)
            $docPaths = [];
            if ($request->hasFile('docs')) {
                foreach ($request->file('docs') as $file) {
                    $docPaths[] = 'storage/' . $file->store("uploads/events{$event->id}/docs", 'public');
                }
            }

            // images (массив изображений)
            $imagePaths = [];
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $img) {
                    $imagePaths[] = 'storage/' . $img->store("uploads/events{$event->id}/images", 'public');
                }
            }

            // videos (массив видео)
            $videoPaths = [];
            if ($request->hasFile('videos')) {
                foreach ($request->file('videos') as $video) {
                    $videoPaths[] = 'storage/' . $video->store("uploads/events{$event->id}/videos", 'public');
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
                ->route(Auth::user()->role . '.events.index', $event)
                ->with('success', 'Мероприятие успешно создано.');
        });
    }

    public function approve($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'approved';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route('admin.events.index', $event)
            ->with('success', 'Мероприятие успешно одобрено.');
    }

    public function reject($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'rejected';
        $event->admin_id = Auth::id();
        $event->save();

        return redirect()
            ->route('admin.events.index', $event)
            ->with('success', 'Мероприятие успешно отклонено.');
    }

    public function archive($id)
    {
        $event = Event::findOrFail($id);
        $event->status = 'archived';
        $event->save();

        return redirect()
            ->route(Auth::user()->role . '.events.index')
            ->with('success', 'Мероприятие успешно перемещено в архив.');
    }

    public function remove($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()
            ->route(Auth::user()->role . '.events.index')
            ->with('success', 'Мероприятие успешно удалено.');
    }

    public function getParticipants($id = null)
    {
        if ($id) {
            $participantsQuery = Participant::where('event_id', $id)
                ->whereHas('event', function ($query) {
                    $query->where('user_id', Auth::id());
                })
                ->with('user');
        } else {
            $participantsQuery = Participant::whereHas('event', function ($query) {
                $query->where('user_id', Auth::id());
            })
                ->with('user');
        }

        $participants = $participantsQuery->orderByDesc('id')->paginate(12);

        return view('admin.events.participants', compact('participants'));
    }

    public function approveParticipant($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->status = 'approved';
        $participant->save();

        return redirect()
            ->back()
            ->with('success', 'Участник успешно одобрен.');
    }

    public function rejectParticipant($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->status = 'rejected';
        $participant->save();

        return redirect()
            ->back()
            ->with('success', 'Участник успешно отклонён.');
    }

    function accurePoints($id)
    {
        $participant = Participant::findOrFail($id);
        $participant->status = 'points_accured';
        $participant->save();
        Points::create([
            'user_id' => $participant->user_id,
            'event_id' => $participant->event_id,
            'partner_id' => $participant->event->user_id,
            'points' => $participant->getPoints(),
            'awarded_by' => Auth::id(),
        ]);
        return redirect()
            ->back()
            ->with('success', 'Баллы участнику успешно начислены.');
    }
}

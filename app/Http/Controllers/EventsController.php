<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEventRequest;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{

    /**
     * Список событий (пагинация + простой поиск)
     */
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $events = Event::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            }))
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        return view('events.index', compact('events', 'q'));
    }

    /**
     * Форма создания
     */
    public function create()
    {
        return view('events.create');
    }

    /**
     * Создание события
     */
    public function store(CreateEventRequest $request)
    {
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

            if ($docPaths || $imagePaths) {
                $event->update([
                    'docs'   => $docPaths ?: [],
                    'images' => $imagePaths ?: [],
                ]);
            }

            return redirect()
                ->route('events.show', $event)
                ->with('success', 'Событие успешно создано.');
        });
    }

    /**
     * Просмотр
     */
    public function show(Event $event)
    {
        return view('events.show', compact('event'));
    }

    /**
     * Форма редактирования
     */
    public function edit(Event $event)
    {
        return view('events.edit', compact('event'));
    }

    /**
     * Обновление (упрощённо; при необходимости сделаем отдельный Request)
     */
    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category' => 'sometimes|string|max:255',
            'type'     => 'sometimes|string|max:255',
            'title'    => 'sometimes|string|max:255',
            'short_description' => 'sometimes|string|max:500',
            'description' => 'nullable|string',
            'address' => 'nullable|string|max:255',
            'settlement' => 'nullable|string|max:255',
            'start' => 'sometimes|date',
            'end'   => 'nullable|date|after_or_equal:start',
            'supervisor_id' => 'nullable|integer',
            'supervisor_name' => 'nullable|string|max:255',
            'supervisor_l_name' => 'nullable|string|max:255',
            'supervisor_phone' => 'nullable|string|max:20',
            'supervisor_email' => 'nullable|email|max:255',
            'web' => 'nullable|url|max:255',
            'telegram' => 'nullable|string|max:255',
            'vk' => 'nullable|string|max:255',
            'roles'  => 'nullable|array',
            'videos' => 'nullable|array',
            'cover'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'docs'   => 'nullable|array',
            'docs.*' => 'file|max:5120',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // нормализация массивов
        if (isset($data['roles']) && is_string($data['roles'])) {
            $data['roles'] = json_decode($data['roles'], true) ?: [];
        }
        if (isset($data['videos']) && is_string($data['videos'])) {
            $data['videos'] = json_decode($data['videos'], true) ?: [];
        }

        // загрузка новых файлов
        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store("uploads/events{$event->id}/cover", 'public');
        }

        if ($request->hasFile('docs')) {
            $docPaths = $event->docs ?? [];
            foreach ($request->file('docs') as $file) {
                $docPaths[] = $file->store("uploads/events{$event->id}/docs", 'public');
            }
            $data['docs'] = $docPaths;
        }

        if ($request->hasFile('images')) {
            $imgPaths = $event->images ?? [];
            foreach ($request->file('images') as $img) {
                $imgPaths[] = $img->store("uploads/events{$event->id}/images", 'public');
            }
            $data['images'] = $imgPaths;
        }

        $event->update($data);

        return back()->with('success', 'Событие обновлено.');
    }

    /**
     * Удаление
     */
    public function destroy(Event $event)
    {
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Событие удалено.');
    }
}

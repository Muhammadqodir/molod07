<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateGrantRequest;
use App\Http\Requests\UpdateGrantRequest;
use App\Models\Grant;
use App\Models\GrantApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageGrantsController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();
        $grants = Grant::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%");
            }))
            ->when($status, fn($qry) => $qry->where('status', $status))
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add partner to each grant
        foreach ($grants as $grant) {
            $grant->partner = User::find($grant->user_id);
        }

        return view('admin.grants.list', compact('grants', 'q'));
    }

    public function index(Request $request)
    {
        return $this->show($request);
    }

    public function preview($id)
    {
        $grant = Grant::findOrFail($id);
        $grant->partner = User::find($grant->user_id);
        return view('pages.grant', compact('grant'));
    }

    public function create()
    {
        return view('admin.grants.create');
    }

    public function store(CreateGrantRequest $request)
    {
        $data = $request->validated();

        // если user_id не прислан — берём из auth
        $data['user_id'] = $data['user_id'] ?? Auth::id();

        return DB::transaction(function () use ($request, $data) {
            // 1) создаём запись без файлов
            $grant = Grant::create([
                'user_id'            => $data['user_id'],
                'category'           => $data['category'],
                'title'              => $data['title'],
                'short_description'  => $data['short_description'],
                'description'        => $data['description'] ?? null,
                'conditions'         => $data['conditions'] ?? null,
                'requirements'       => $data['requirements'] ?? null,
                'reward'             => $data['reward'] ?? null,
                'address'            => $data['address'] ?? null,
                'settlement'         => $data['settlement'] ?? null,
                'deadline'           => $data['deadline'] ?? null,
                'web'                => $data['web'] ?? null,
                'telegram'           => $data['telegram'] ?? null,
                'vk'                 => $data['vk'] ?? null,
                'status'             => $data['status'] ?? 'pending',
                'admin_id'           => Auth::id(),
            ]);

            // 2) файлы
            // cover (один)
            if ($request->hasFile('cover')) {
                $path = $request->file('cover')->store("uploads/grants{$grant->id}/cover", 'public');
                $grant->update(['cover' => 'storage/' . $path]);
            }

            // docs (массив любых файлов)
            $docPaths = [];
            if ($request->hasFile('docs')) {
                foreach ($request->file('docs') as $file) {
                    $docPaths[] = 'storage/' . $file->store("uploads/grants{$grant->id}/docs", 'public');
                }
            }

            if ($docPaths) {
                $grant->update(['docs' => $docPaths]);
            }

            return redirect()
                ->route('admin.grants.index')
                ->with('success', 'Грант успешно создан.');
        });
    }

    public function edit($id)
    {
        $grant = Grant::findOrFail($id);

        // Check if user can edit this grant
        if (Auth::user()->role !== 'admin' && $grant->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Add partner information
        $grant->partner = User::find($grant->user_id);

        return view('admin.grants.edit', compact('grant'));
    }

    public function update(UpdateGrantRequest $request, $id)
    {
        $grant = Grant::findOrFail($id);

        // Check if user can edit this grant
        if (Auth::user()->role !== 'admin' && $grant->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $v = $request->validated();

        return DB::transaction(function () use ($request, $v, $grant) {
            // Handle cover image upload if new file is provided
            if ($request->hasFile('cover')) {
                // Delete old cover if exists
                if ($grant->cover && file_exists(public_path($grant->cover))) {
                    unlink(public_path($grant->cover));
                }

                $coverFile = $request->file('cover');
                $coverPath = $coverFile->store("uploads/grants{$grant->id}/cover", 'public');
                $v['cover'] = 'storage/' . $coverPath;
            }

            // Handle file uploads for docs
            if ($request->hasFile('docs')) {
                $docPaths = [];
                foreach ($request->file('docs') as $file) {
                    $docPaths[] = 'storage/' . $file->store("uploads/grants{$grant->id}/docs", 'public');
                }
                $v['docs'] = array_merge($grant->docs ?? [], $docPaths);
            }

            // Update the grant entry
            $grant->update($v);

            return redirect()
                ->route(Auth::user()->role . '.grants.index')
                ->with('success', 'Грант успешно обновлен.');
        });
    }

    public function approve($id)
    {
        $grant = Grant::findOrFail($id);
        $grant->status = 'approved';
        $grant->admin_id = Auth::id();
        $grant->save();

        return redirect()
            ->route('admin.grants.index')
            ->with('success', 'Грант успешно одобрен.');
    }

    public function reject($id)
    {
        $grant = Grant::findOrFail($id);
        $grant->status = 'rejected';
        $grant->admin_id = Auth::id();
        $grant->save();

        return redirect()
            ->route('admin.grants.index')
            ->with('success', 'Грант успешно отклонен.');
    }

    public function archive($id)
    {
        $grant = Grant::findOrFail($id);
        $grant->status = 'archived';
        $grant->save();

        return redirect()
            ->route('admin.grants.index')
            ->with('success', 'Грант успешно перемещен в архив.');
    }

    public function getResponses(Request $request)
    {
        $q = $request->string('q')->toString();
        $isPartner = Auth::user()->role === 'partner';
        $responsesQuery = GrantApplication::query()
            ->with(['grant', 'user', 'user.youthProfile'])
            ->whereHas('grant', function ($query) use ($isPartner) {
                if ($isPartner) {
                    $query->where('user_id', Auth::id());
                }
            });
        if ($q) {
            $responsesQuery->whereHas('grant', function ($grantQuery) use ($q) {
                $grantQuery->where('title', 'like', "%{$q}%");
            })->orWhereHas('user.youthProfile', function ($userQuery) use ($q) {
                $userQuery->where('name', 'like', "%{$q}%")
                    ->orWhere('l_name', 'like', "%{$q}%");
            });
        }
        $responses = $responsesQuery
            ->orderByDesc('created_at')
            ->paginate(10)
            ->appends($request->query());
        return view("admin.grants.responses", compact('responses', 'q'));
    }
}

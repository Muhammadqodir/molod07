<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use Illuminate\Http\Request;

class ManageMinistriesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();

        $query = Ministry::query()
            ->when($q, fn($qry) => $qry->where('title', 'like', "%{$q}%"));

        $ministries = $query
            ->withCount('opportunities')
            ->orderBy('title')
            ->paginate(12)
            ->appends($request->query());

        return view('admin.ministries.index', compact('ministries', 'q'));
    }

    public function create()
    {
        return view('admin.ministries.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Ministry::create($request->only(['title', 'description']));

        return redirect()->route('admin.ministries.index')
            ->with('success', 'Министерство успешно создано');
    }

    public function edit(Ministry $ministry)
    {
        return view('admin.ministries.edit', compact('ministry'));
    }

    public function update(Request $request, Ministry $ministry)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $ministry->update($request->only(['title', 'description']));

        return redirect()->route('admin.ministries.index')
            ->with('success', 'Министерство успешно обновлено');
    }

    public function destroy(Ministry $ministry)
    {
        // Check if ministry has opportunities
        if ($ministry->opportunities()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Невозможно удалить министерство с возможностями');
        }

        $ministry->delete();

        return redirect()->route('admin.ministries.index')
            ->with('success', 'Министерство успешно удалено');
    }
}

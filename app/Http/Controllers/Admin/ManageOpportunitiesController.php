<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ministry;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class ManageOpportunitiesController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->string('q')->toString();
        $ministry_id = $request->query('ministry_id');

        $query = Opportunity::query()
            ->with('ministry')
            ->when($q, fn($qry) => $qry->where('program_name', 'like', "%{$q}%"))
            ->when($ministry_id, fn($qry) => $qry->where('ministry_id', $ministry_id));

        $opportunities = $query
            ->orderBy('program_name')
            ->paginate(12)
            ->appends($request->query());

        $ministries = Ministry::orderBy('title')->get();

        return view('admin.opportunities.index', compact('opportunities', 'q', 'ministries', 'ministry_id'));
    }

    public function create()
    {
        $ministries = Ministry::orderBy('title')->get();
        return view('admin.opportunities.create', compact('ministries'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ministry_id' => 'required|exists:ministries,id',
            'cover' => 'nullable|image|mimes:jpeg,png|max:2048',
            'program_name' => 'required|string|max:255',
            'participation_conditions' => 'required|string',
            'implementation_period' => 'required|string',
            'required_documents' => 'required|string',
            'legal_documents' => 'nullable|array',
            'legal_documents.*.title' => 'nullable|string|max:500',
            'legal_documents.*.link' => 'nullable|url',
            'responsible_person.name' => 'required|string|max:255',
            'responsible_person.position' => 'required|string|max:255',
            'responsible_person.contact' => 'required|string|max:255',
        ]);

        // Handle cover image upload
        if ($request->hasFile('cover')) {
            $coverFile = $request->file('cover');
            $coverPath = $coverFile->store('opportunities_covers', 'public');
            $validated['cover_image'] = 'storage/' . $coverPath;
        }

        // Filter out empty legal documents
        if (!empty($validated['legal_documents'])) {
            $validated['legal_documents'] = array_filter($validated['legal_documents'], function ($doc) {
                return !empty(trim($doc['title'] ?? '')) && !empty(trim($doc['link'] ?? ''));
            });
            // Re-index the array to avoid gaps
            $validated['legal_documents'] = array_values($validated['legal_documents']);
        }

        // Remove cover from validated data as we store it as cover_image
        unset($validated['cover']);

        Opportunity::create($validated);

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Возможность успешно создана');
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load('ministry');
        return view('admin.opportunities.show', compact('opportunity'));
    }

    public function edit(Opportunity $opportunity)
    {
        $ministries = Ministry::orderBy('title')->get();
        return view('admin.opportunities.edit', compact('opportunity', 'ministries'));
    }

    public function update(Request $request, Opportunity $opportunity)
    {
        $validated = $request->validate([
            'ministry_id' => 'required|exists:ministries,id',
            'cover' => 'nullable|image|mimes:jpeg,png|max:2048',
            'program_name' => 'required|string|max:255',
            'participation_conditions' => 'required|string',
            'implementation_period' => 'required|string',
            'required_documents' => 'required|string',
            'legal_documents' => 'nullable|array',
            'legal_documents.*.title' => 'nullable|string|max:500',
            'legal_documents.*.link' => 'nullable|url',
            'responsible_person.name' => 'required|string|max:255',
            'responsible_person.position' => 'required|string|max:255',
            'responsible_person.contact' => 'required|string|max:255',
        ]);

        // Handle cover image upload if new file is provided
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($opportunity->cover_image && file_exists(public_path($opportunity->cover_image))) {
                unlink(public_path($opportunity->cover_image));
            }

            $coverFile = $request->file('cover');
            $coverPath = $coverFile->store('opportunities_covers', 'public');
            $validated['cover_image'] = 'storage/' . $coverPath;
        }

        // Filter out empty legal documents
        if (!empty($validated['legal_documents'])) {
            $validated['legal_documents'] = array_filter($validated['legal_documents'], function ($doc) {
                return !empty(trim($doc['title'] ?? '')) && !empty(trim($doc['link'] ?? ''));
            });
            // Re-index the array to avoid gaps
            $validated['legal_documents'] = array_values($validated['legal_documents']);
        }

        // Remove cover from validated data as we store it as cover_image
        unset($validated['cover']);

        $opportunity->update($validated);

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Возможность успешно обновлена');
    }

    public function destroy(Opportunity $opportunity)
    {
        $opportunity->delete();

        return redirect()->route('admin.opportunities.index')
            ->with('success', 'Возможность успешно удалена');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManageCoursesController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();

        $courses = Course::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhere('category', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add user (creator) information to each course
        foreach ($courses as $course) {
            $course->creator = User::find($course->user_id);
            $course->admin = User::find($course->admin_id);
        }

        return view('admin.courses.list', compact('courses', 'q'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(CreateCourseRequest $request)
    {
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('uploads/courses/covers', 'public');
            $validated['cover'] = 'storage/' . $coverPath;
        }

        // Set admin_id to current authenticated admin
        $validated['user_id'] = Auth::id();
        $validated['admin_id'] = Auth::id();
        $validated['status'] = 'approved'; // Admin-created courses are auto-approved

        $course = Course::create($validated);

        return redirect()->route('admin.education.index')
            ->with('success', 'Курс успешно создан!');
    }

    public function preview($id)
    {
        $course = Course::findOrFail($id);
        $course->creator = User::find($course->user_id);
        $course->admin = User::find($course->admin_id);

        return view('pages.course', compact('course'));
    }

    public function approve($id)
    {
        $course = Course::findOrFail($id);
        $course->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Курс одобрен!');
    }

    public function reject($id)
    {
        $course = Course::findOrFail($id);
        $course->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Курс отклонен!');
    }

    public function archive($id)
    {
        $course = Course::findOrFail($id);
        $course->update([
            'status' => 'archived',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Курс архивирован!');
    }

    public function edit($id)
    {
        $course = Course::findOrFail($id);
        return view('admin.courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, $id)
    {
        $course = Course::findOrFail($id);
        $validated = $request->validated();

        // Handle file upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($course->cover && Storage::disk('public')->exists(str_replace('storage/', '', $course->cover))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $course->cover));
            }

            $coverPath = $request->file('cover')->store('uploads/courses/covers', 'public');
            $validated['cover'] = 'storage/' . $coverPath;
        }

        // Update course
        $course->update($validated);

        return redirect()->route('admin.education.index')
            ->with('success', 'Курс успешно обновлен!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);

        // Delete cover file if exists
        if ($course->cover && Storage::disk('public')->exists($course->cover)) {
            Storage::disk('public')->delete($course->cover);
        }

        $course->delete();

        return redirect()->back()->with('success', 'Курс удален!');
    }
}

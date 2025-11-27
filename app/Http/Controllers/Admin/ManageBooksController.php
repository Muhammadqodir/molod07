<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ManageBooksController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();

        $books = Book::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('author', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add user (creator) information to each book
        foreach ($books as $book) {
            $book->creator = User::find($book->user_id);
            $book->admin = User::find($book->admin_id);
        }

        return view('admin.books.list', compact('books', 'q'));
    }

    public function create()
    {
        return view('admin.books.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('cover')) {
            $coverPath = $request->file('cover')->store('uploads/books/covers', 'public');
            $validated['cover'] = 'storage/' . $coverPath;
        }

        // Set admin_id to current authenticated admin
        $validated['user_id'] = Auth::id();
        $validated['admin_id'] = Auth::id();
        $validated['status'] = 'approved'; // Admin-created books are auto-approved

        $book = Book::create($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Книга успешно добавлена!');
    }

    public function approve($id)
    {
        $book = Book::findOrFail($id);
        $book->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Книга одобрена!');
    }

    public function reject($id)
    {
        $book = Book::findOrFail($id);
        $book->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Книга отклонена!');
    }

    public function archive($id)
    {
        $book = Book::findOrFail($id);
        $book->update([
            'status' => 'archived',
            'admin_id' => Auth::id()
        ]);

        return redirect()->back()->with('success', 'Книга архивирована!');
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        return view('admin.books.edit', compact('book'));
    }

    public function update(Request $request, $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|url',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle file upload
        if ($request->hasFile('cover')) {
            // Delete old cover if exists
            if ($book->cover && Storage::disk('public')->exists(str_replace('storage/', '', $book->cover))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $book->cover));
            }

            $coverPath = $request->file('cover')->store('uploads/books/covers', 'public');
            $validated['cover'] = 'storage/' . $coverPath;
        }

        // Update book
        $book->update($validated);

        return redirect()->route('admin.books.index')
            ->with('success', 'Книга успешно обновлена!');
    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);

        // Delete cover file if exists
        if ($book->cover && Storage::disk('public')->exists(str_replace('storage/', '', $book->cover))) {
            Storage::disk('public')->delete(str_replace('storage/', '', $book->cover));
        }

        $book->delete();

        return redirect()->back()->with('success', 'Книга удалена!');
    }
}

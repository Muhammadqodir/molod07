<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrantApplication;
use App\Models\Grant;
use Illuminate\Support\Facades\Auth;

class GrantApplicationController extends Controller
{
    public function store(Request $request, $grantId)
    {
        $grant = Grant::findOrFail($grantId);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'comment' => 'nullable|string',
        ]);
        $application = GrantApplication::create([
            'grant_id' => $grant->id,
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'comment' => $data['comment'] ?? null,
            'submitted_at' => now(),
        ]);
        return redirect()->back()->with('success', 'Заявка отправлена!');
    }
}

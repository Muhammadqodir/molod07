<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        $validationRules = [
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
            'screenshot' => 'nullable|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ];

        // Add validation for guest users
        if (!Auth::check()) {
            $validationRules['guest_name'] = 'required|string|max:255';
            $validationRules['guest_email'] = 'required|email|max:255';
        }

        $request->validate($validationRules);

        $screenshotPath = null;
        if ($request->hasFile('screenshot')) {
            $screenshotPath = $request->file('screenshot')->store('feedback', 'public');
        }

        $feedbackData = [
            'subject' => $request->subject,
            'message' => $request->message,
            'screenshot' => $screenshotPath,
            'status' => 'new',
        ];

        if (Auth::check()) {
            $feedbackData['user_id'] = Auth::id();
        } else {
            $feedbackData['guest_name'] = $request->guest_name;
            $feedbackData['guest_email'] = $request->guest_email;
        }

        Feedback::create($feedbackData);

        return response()->json([
            'success' => true,
            'message' => 'Ваше сообщение успешно отправлено! Мы рассмотрим его в ближайшее время.'
        ]);
    }
}

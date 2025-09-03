<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\Vacancy;
use App\Models\VacancyResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VacancyController extends Controller
{
    public function myVacancies()
    {
        $userId = Auth::id();
        $responses = VacancyResponse::where('user_id', $userId)
            ->with(['vacancy', 'vacancy.user'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('youth.vacancies.list', compact('responses'));
    }

    public function respond(Request $request, $id)
    {
        $request->validate([
            'resume' => 'required|file|mimes:pdf,doc,docx|max:5120', // 5MB max
            'cover_letter' => 'required|string|max:2000',
            'prefered_contact' => 'required|in:phone,email,telegram'
        ]);

        $vacancy = Vacancy::findOrFail($id);
        $userId = Auth::id();

        // Check if user already responded to this vacancy
        $existingResponse = VacancyResponse::where('user_id', $userId)
            ->where('vacancy_id', $id)
            ->first();

        if ($existingResponse) {
            return redirect()->back()->with('error', 'Вы уже откликнулись на эту вакансию');
        }

        // Handle file upload
        $resumePath = null;
        if ($request->hasFile('resume')) {
            $file = $request->file('resume');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $resumePath = 'uploads/' . $filename;
        }

        // Create vacancy response record
        VacancyResponse::create([
            'user_id' => $userId,
            'vacancy_id' => $id,
            'resume_path' => $resumePath,
            'cover_letter' => $request->input('cover_letter'),
            'prefered_contact' => $request->input('prefered_contact'),
            'response' => '' // Empty initially, filled by partner later
        ]);

        return redirect()->back()->with('success', 'Ваш отклик успешно отправлен! Работодатель свяжется с вами выбранным способом связи.');
    }
}

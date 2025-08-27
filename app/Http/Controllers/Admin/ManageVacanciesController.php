<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVacanciesRequest;
use App\Models\Vacancy;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageVacanciesController extends Controller
{
    public function show(Request $request)
    {
        $status = $request->query('status', $request->route('status'));
        $q = $request->string('q')->toString();
        $vacancies = Vacancy::query()
            ->when($q, fn($qry) => $qry->where(function ($w) use ($q) {
                $w->where('title', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('org_name', 'like', "%{$q}%");
            }))
            ->where('status', $status)
            ->orderByDesc('id')
            ->paginate(12)
            ->appends($request->query());

        // Add user and admin fields to each vacancy
        foreach ($vacancies as $vacancy) {
            $vacancy->user = User::find($vacancy->user_id);
            $vacancy->admin = User::find($vacancy->admin_id);
        }

        return view('admin.vacancies.list', compact('vacancies', 'q'));
    }

    public function preview($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->user = User::find($vacancy->user_id);
        $vacancy->admin = User::find($vacancy->admin_id);
        return view('pages.vacancy', compact('vacancy'));
    }

    public function create()
    {
        return view('admin.vacancies.create');
    }

    public function store(CreateVacanciesRequest $request)
    {
        $v = $request->validated();

        // если user_id не прислан — берём из auth
        $v['user_id'] = $v['user_id'] ?? Auth::id();

        $vacancy = new Vacancy([
            'user_id' => $v['user_id'],
            'category' => $v['category'],
            'title' => $v['title'],
            'description' => $v['description'],
            'salary_from' => $v['salary_from'] ?? null,
            'salary_to' => $v['salary_to'] ?? null,
            'salary_negotiable' => $v['salary_negotiable'] ?? false,
            'type' => $v['type'],
            'experience' => $v['experience'] ?? null,
            'org_name' => $v['org_name'],
            'org_phone' => $v['org_phone'] ?? null,
            'org_email' => $v['org_email'] ?? null,
            'org_address' => $v['org_address'] ?? null,
            'status' => $v['status'] ?? 'approved',
            'admin_id' => Auth::id(),
        ]);
        $vacancy->save();

        return redirect()
            ->route('admin.vacancies.index', $vacancy)
            ->with('success', 'Вакансия успешно создана.');
    }

    public function approve($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->status = 'approved';
        $vacancy->admin_id = Auth::id();
        $vacancy->save();

        return redirect()
            ->route('admin.vacancies.index', $vacancy)
            ->with('success', 'Вакансия успешно одобрена.');
    }

    public function reject($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->status = 'rejected';
        $vacancy->admin_id = Auth::id();
        $vacancy->save();

        return redirect()
            ->route('admin.vacancies.index', $vacancy)
            ->with('success', 'Вакансия успешно отклонена.');
    }

    public function archive($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->status = 'archived';
        $vacancy->save();

        return redirect()
            ->route('admin.vacancies.index')
            ->with('success', 'Вакансия успешно перемещена в архив.');
    }
}

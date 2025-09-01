<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PagesController extends Controller
{
    function main()
    {

        $events = Event::where('status', 'approved')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $vacancies = Vacancy::where('status', 'approved')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('pages.main', compact('events', 'vacancies'));
    }

    function eventPage($id)
    {
        $event = Event::findOrFail($id);
        $event->partner = User::find($event->user_id);
        $event->supervisor = User::find($event->supervisor_id);
        return view('pages.event', compact('event'));
    }

    function vacancyPage($id)
    {
        $vacancy = Vacancy::findOrFail($id);
        $vacancy->admin = User::find($vacancy->admin_id);
        return view('pages.vacancy', compact('vacancy'));
    }

    function applyFilters($query, $request)
    {
        // Filter by category if set and not 'Все'
        $category = $request->input('category', 'Все');
        if ($category !== 'Все') {
            $query->where('category', $category);
        }

        // Sort by 'popular' or 'date'
        $sort = $request->input('sort', 'popular');
        if ($sort === 'date') {
            $query->orderByDesc('created_at'); // Change 'date' to your actual date column
        } else {
            // $query->orderByDesc('views'); // Change 'views' to your actual popularity column
        }
        return $query;
    }

    function eventsList(Request $request)
    {
        $query = Event::where('status', 'approved');
        $query = $this->applyFilters($query, $request);
        $items = $query->paginate(10);
        $entity = 'events';
        $count = $items->total();
        $title = $request->input('category', 'Все') === 'Все' ? 'Все мероприятия' : $request->input('category', 'Все');
        return view('pages.list', compact('items', 'entity', 'count', 'title'));
    }

    function vacanciesList(Request $request)
    {
        $query = Vacancy::where('status', 'approved');
        $query = $this->applyFilters($query, $request);
        $items = $query->paginate(10);
        $entity = 'vacancies';
        $count = $items->total();
        $title = $request->input('category', 'Все') === 'Все' ? 'Все вакансии' : $request->input('category', 'Все');
        return view('pages.list', compact('items', 'entity', 'count', 'title'));
    }

    function aboutPage()
    {
        return view('pages.about');
    }
    function contactsPage()
    {
        return view('pages.contacts');
    }
    function documentsPage()
    {
        return view('pages.documents');
    }
}

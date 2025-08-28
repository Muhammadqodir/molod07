<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;

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

    function eventsList(Request $request){
        $query = Event::where('status', 'approved');

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

        $items = $query->paginate(10);
        $title = $category === 'Все' ? 'Все мероприятия' : $category;
        $entity = 'events';
        $count = $items->total();
        return view('pages.list', compact('items', 'title', 'entity', 'count'));
    }
}

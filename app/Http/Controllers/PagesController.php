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
}

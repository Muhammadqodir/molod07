<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Event;
use App\Models\News;
use App\Models\Participant;
use App\Models\Podcast;
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

        $courses = Course::where('status', 'approved')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        $news = News::where('status', 'approved')
            ->orderByDesc('id')
            ->limit(6)
            ->get();

        return view('pages.main', compact('events', 'vacancies', 'courses', 'news'));
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

    function coursePage($id)
    {
        $course = Course::findOrFail($id);
        $course->creator = User::find($course->user_id);
        $course->admin = User::find($course->admin_id);
        return view('pages.course', compact('course'));
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

    function coursesList(Request $request)
    {
        $query = Course::where('status', 'approved');
        $query = $this->applyFilters($query, $request);
        $items = $query->paginate(10);
        $entity = 'courses';
        $count = $items->total();
        $title = $request->input('category', 'Все') === 'Все' ? 'Все курсы' : $request->input('category', 'Все');
        return view('pages.list', compact('items', 'entity', 'count', 'title'));
    }

    function newsList(Request $request)
    {
        $query = News::where('status', 'approved');
        $query = $this->applyFilters($query, $request);
        $items = $query->paginate(10);
        $entity = 'news';
        $count = $items->total();
        $title = $request->input('category', 'Все') === 'Все' ? 'Все новости' : $request->input('category', 'Все');
        return view('pages.list', compact('items', 'entity', 'count', 'title'));
    }

    function podcastsList(Request $request)
    {
        $query = Podcast::where('status', 'approved');
        $query = $this->applyFilters($query, $request);
        $items = $query->paginate(10);
        $entity = 'podcasts';
        $count = $items->total();
        $title = $request->input('category', 'Все') === 'Все' ? 'Все подкасты' : $request->input('category', 'Все');
        return view('pages.list', compact('items', 'entity', 'count', 'title'));
    }

    function newsPage($id)
    {
        $news = News::findOrFail($id);
        $news->author = User::find($news->user_id);
        $news->admin = User::find($news->admin_id);
        return view('pages.news', compact('news'));
    }

    function podcastPage($id)
    {
        $podcast = Podcast::findOrFail($id);
        $podcast->author = User::find($podcast->user_id);
        $podcast->admin = User::find($podcast->admin_id);
        return view('pages.podcast', compact('podcast'));
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
    function privacyPolicyPage()
    {
        return view('pages.privacy-policy');
    }

    function partnersList()
    {
        $partners = User::where('role', 'partner')
            ->with('partnersProfile')
            ->whereHas('partnersProfile')
            ->get();

        return view('pages.partners', compact('partners'));
    }

    function partnerPage($id)
    {
        $partner = User::where('role', 'partner')
            ->with('partnersProfile')
            ->findOrFail($id);

        $events = Event::where('user_id', $id)
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->get();

        $vacancies = Vacancy::where('user_id', $id)
            ->where('status', 'approved')
            ->orderByDesc('created_at')
            ->get();

        return view('pages.partner', compact('partner', 'events', 'vacancies'));
    }
}

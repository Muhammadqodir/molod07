<?php

namespace App\Http\Controllers;

use App\Models\Ministry;
use App\Models\Opportunity;
use Illuminate\Http\Request;

class OpportunityController extends Controller
{
    public function index()
    {
        $ministries = Ministry::with(['opportunities' => function ($query) {
            $query->orderBy('program_name');
        }])
        ->whereHas('opportunities')
        ->orderBy('title')
        ->get();

        return view('opportunities.index', compact('ministries'));
    }

    public function show(Opportunity $opportunity)
    {
        $opportunity->load('ministry');
        return view('opportunities.show', compact('opportunity'));
    }

    public function byMinistry(Ministry $ministry)
    {
        $opportunities = $ministry->opportunities()
            ->orderBy('program_name')
            ->paginate(12);

        return view('opportunities.by-ministry', compact('ministry', 'opportunities'));
    }
}

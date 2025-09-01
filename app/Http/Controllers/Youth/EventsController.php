<?php

namespace App\Http\Controllers\Youth;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Participant;
use App\Models\User;
use App\Models\Vacancy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventsController extends Controller
{

    function myEvents()
    {
        $userId = Auth::id();
        $participations = Participant::where('user_id', $userId)->with('event')->paginate(10);

        // Add partner field to each event (similar to what's done in other controllers)
        foreach ($participations as $item) {
            if ($item->event) {
                $item->event->partner = User::find($item->event->user_id);
            }
        }

        return view('youth.events.list', compact('participations'));
    }

    function registerForEvent(Request $request, $id)
    {

        $request->validate([
            'role' => 'required|integer'
        ]);

        $event = Event::findOrFail($id);
        $userId = Auth::id();

        // Check if user already registered for this event
        $existingParticipation = Participant::where('user_id', $userId)
            ->where('event_id', $id)
            ->first();

        if ($existingParticipation) {
            return redirect()->back()->with('error', 'Вы уже подали заявку на это мероприятие');
        }

        // Get the roles from the event
        $roles = json_decode($event->roles ?? '[]', true);
        $selectedRoleIndex = $request->input('role');

        if (!isset($roles[$selectedRoleIndex])) {
            return redirect()->back()->with('error', 'Выбранная роль не найдена');
        }

        $selectedRole = $roles[$selectedRoleIndex];

        // Create participant record
        Participant::create([
            'user_id' => $userId,
            'event_id' => $id,
            'status' => 'pending',
            'role' => json_encode([
                'index' => $selectedRoleIndex,
                'title' => $selectedRole['title'] ?? 'Роль ' . ($selectedRoleIndex + 1),
                'points' => $selectedRole['points'] ?? 0
            ]),
            'shift' => null
        ]);

        return redirect()->back()->with('success', 'Ваша заявка успешно подана! С вами свяжется куратор мероприятия.');
    }
}

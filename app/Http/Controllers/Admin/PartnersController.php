<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PartnersController extends Controller
{

    public function show()
    {
        $query = User::where('role', 'partner')
            ->join('partners_profiles', 'users.id', '=', 'partners_profiles.user_id')
            ->select('users.*', 'partners_profiles.org_name');

        if ($search = request('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('partners_profiles.org_name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $partners = $query
            ->orderByDesc('users.id')
            ->paginate(10)
            ->appends(request()->query());

        return view('admin.manage.partners', compact('partners'));
    }

    public function remove(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $partner = User::where('role', 'partner')->findOrFail($id);
        $partner->partnersProfile()->delete();
        $partner->delete();

        return redirect()->route('admin.manage.partners')->with('success', 'Пользователь успешно удален.');
    }


    public function block(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $partner = User::where('role', 'partner')->findOrFail($id);

        $partner->is_blocked = true;
        $partner->save();

        return redirect()->route('admin.manage.partners')->with('success', 'Пользователь заблокирован.');
    }

    public function unblock(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $partner = User::where('role', 'partner')->findOrFail($id);

        $partner->is_blocked = false;
        $partner->save();

        return redirect()->route('admin.manage.partners')->with('success', 'Пользователь разблокирован.');
    }
}

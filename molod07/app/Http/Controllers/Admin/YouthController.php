<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class YouthController extends Controller
{

    public function show()
    {
        $query = User::where('role', 'youth')
            ->join('youth_profiles', 'users.id', '=', 'youth_profiles.user_id')
            ->select('users.*', 'youth_profiles.name', 'youth_profiles.l_name', 'youth_profiles.f_name');

        if ($search = request('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('youth_profiles.name', 'like', "%{$search}%")
                    ->orWhere('youth_profiles.l_name', 'like', "%{$search}%")
                    ->orWhere('youth_profiles.f_name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $youth = $query
            ->orderByDesc('users.id')
            ->paginate(10)
            ->appends(request()->query());

        return view('admin.manage.youth', compact('youth'));
    }

    public function remove(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $youth = User::where('role', 'youth')->findOrFail($id);
        $youth->youthProfile()->delete();
        $youth->delete();

        return redirect()->route('admin.manage.youth')->with('success', 'Пользователь успешно удален.');
    }


    public function block(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $youth = User::where('role', 'youth')->findOrFail($id);

        $youth->is_blocked = true;
        $youth->save();

        return redirect()->route('admin.manage.youth')->with('success', 'Пользователь заблокирован.');
    }

    public function unblock(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $youth = User::where('role', 'youth')->findOrFail($id);

        $youth->is_blocked = false;
        $youth->save();

        return redirect()->route('admin.manage.youth')->with('success', 'Пользователь разблокирован.');
    }
}

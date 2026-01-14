<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdministratorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdministratorsController extends Controller
{
    public const PERMISSIONS = [
        'admin.manage.administrators' => "Администраторы",
        'admin.manage.youth' => "Молодежи",
        'admin.manage.partners' => "Партнеры",
        'admin.support' => "Поддержка",
        'admin.feed.events' => "Мероприятии",
        'admin.feed.news' => "Новости",
        'admin.feed.grants' => "Гранты",
        'admin.feed.courses' => "Курсы",
        'admin.feed.tests' => "Тесты",
        'admin.coins' => "Баллы",
        'admin.employment.vacancies' => "Вакансии",
        'admin.employment.responses' => "Отклики(вакансиям)",
        'admin.documents' => "Документы",
        'admin.blacklist' => "Черный список",
    ];

    public function show()
    {
        $query = User::where('role', 'admin')
            ->join('admins_profiles', 'users.id', '=', 'admins_profiles.user_id')
            ->select('users.*', 'admins_profiles.name', 'admins_profiles.l_name', 'admins_profiles.f_name');

        if ($search = request('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('admins_profiles.name', 'like', "%{$search}%")
                    ->orWhere('admins_profiles.l_name', 'like', "%{$search}%")
                    ->orWhere('admins_profiles.f_name', 'like', "%{$search}%")
                    ->orWhere('users.email', 'like', "%{$search}%");
            });
        }

        $admins = $query
            ->orderByDesc('users.id')
            ->paginate(10)
            ->appends(request()->query());

        return view('admin.manage.administrators', compact('admins'));
    }

    public function createShow()
    {
        $options = [];

        foreach (self::PERMISSIONS as $key => $label) {
            $options[] = ['value' => $key, 'label' => $label];
        }
        return view('admin.manage.create-administrator', [
            'permissions' => $options
        ]);
    }

    public function createAdminPost(CreateAdministratorRequest $request)
    {

        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
        ]);

        $pic_url = "";
        // Handle file upload
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $filename = uniqid('pic_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $pic_url = $filename;
        }

        $user->adminsProfile()->create([
            'name' => $validated['name'],
            'l_name' => $validated['l_name'],
            'f_name' => $validated['f_name'] ?? null,
            'phone' => $validated['phone'],
            'permissions' => json_encode($validated['permissions']),
            'pic' => $pic_url,
        ]);

        return redirect()->route('admin.manage.administrators')->with('success', 'Администратор успешно зарегистрирован.');
    }

    public function remove(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $admin = User::where('role', 'admin')->findOrFail($id);
        $admin->adminsProfile()->delete();
        $admin->delete();

        return redirect()->route('admin.manage.administrators')->with('success', 'Администратор успешно удален.');
    }

    public function block(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->is_blocked = true;
        $admin->save();

        return redirect()->route('admin.manage.administrators')->with('success', 'Администратор заблокирован.');
    }

    public function unblock(Request $request)
    {
        if (!isset($request["id"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $admin = User::where('role', 'admin')->findOrFail($id);

        $admin->is_blocked = false;
        $admin->save();

        return redirect()->route('admin.manage.administrators')->with('success', 'Администратор разблокирован.');
    }

    public function generateNewPassword(Request $request)
    {
        if (!isset($request["id"]) || !isset($request["password"])) {
            abort(400, 'Bad Request');
        }
        $id = $request["id"];
        $admin = User::where('role', 'admin')->findOrFail($id);

        // Validate password
        $request->validate([
            'password' => 'required|string|min:6',
        ]);

        // Update the user's password
        $admin->password = Hash::make($request->password);
        $admin->save();

        return redirect()->route('admin.manage.administrators')->with('success', "Пароль для {$admin->getFullName()} успешно изменен.");
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\YouthRegisterRequest;
use App\Models\User;
use App\Models\Profiles\YouthProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterYouthController extends Controller
{
    public function show()
    {
        return view('youth.reg');
    }

    public function register(YouthRegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'youth',
        ]);

        $user->youthProfile()->create([
            'name' => $validated['name'],
            'l_name' => $validated['l_name'],
            'f_name' => $validated['f_name'] ?? null,
            'address' => $validated['address'],
            'bday' => $validated['bday'] ?? null,
            'sex' => $validated['sex'] ?? null,
            'phone' => $validated['phone'] ?? null,
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Регистрация прошла успешно!');
    }
}

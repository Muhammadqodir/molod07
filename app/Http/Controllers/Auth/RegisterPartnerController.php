<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\PartnerRegisterRequest;
use App\Http\Requests\YouthRegisterRequest;
use App\Models\User;
use App\Models\Profiles\YouthProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterPartnerController extends Controller
{
    public function show()
    {
        return view('partner.reg');
    }

    public function register(PartnerRegisterRequest $request)
    {
        $validated = $request->validated();

        $user = User::create([
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'partner',
        ]);

        $user->partnersProfile()->create([
            'org_name' => $validated['org_name'],
            'person_name' => $validated['person_name'],
            'person_lname' => $validated['person_lname'] ?? null,
            'org_address' => $validated['org_address'],
            'phone' => $validated['phone'],
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Регистрация прошла успешно!');
    }
}

<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateYouthProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;

class YouthProfileController extends Controller
{

    public function show()
    {
        return view('youth.profile');
    }

    public function updateProfile(UpdateYouthProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->youthProfile; // assumes relation youthProfile exists

        $data = $request->validated();

        // Handle file upload
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            $filename = uniqid('pic_') . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $data['pic'] = $filename;
        }

        if ($request->boolean('remove_pic')) {
            $data['pic'] = null;
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Профиль успешно обновлен!');
    }

    public function deleteProfile(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $profile = $user->youthProfile;

        try {
            $user->delete();

            // Log out the user after successful deletion
            Auth::logout();

            // Invalidate the session
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('main')->with('success', 'Ваш профиль был успешно удален.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Произошла ошибка при удалении профиля. Попробуйте позже.');
        }
    }
}

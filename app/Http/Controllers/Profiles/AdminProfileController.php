<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminProfileController extends Controller
{
    public function show()
    {
        return view('admin.profile');
    }

    public function updateProfile(UpdateAdminProfileRequest $request)
    {
        $user = Auth::user();
        $profile = $user->adminsProfile;

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
}

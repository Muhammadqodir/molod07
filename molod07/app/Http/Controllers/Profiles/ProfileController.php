<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'youth':
                return app(YouthProfileController::class)->show();
            case 'partner':
                return response('in development');
                // return app(PartnerProfileController::class)->show();
            case 'admin':
                return app(AdminProfileController::class)->show();
            default:
                abort(403, 'Unknown user type');
        }
    }

    public function update()
    {
        $user = Auth::user();

        switch ($user->role) {
            case 'youth':
                return app(YouthProfileController::class)->updateProfile(request());
            case 'partner':
                return response('in development');
                // return app(PartnerProfileController::class)->show();
            case 'admin':
                return app(AdminProfileController::class)->updateProfile(request());
            default:
                abort(403, 'Unknown user type');
        }
    }
}

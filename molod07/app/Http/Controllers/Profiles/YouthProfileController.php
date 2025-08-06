<?php

namespace App\Http\Controllers\Profiles;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class YouthProfileController extends Controller
{

    public function show()
    {
        return view('youth.profile');
    }
}

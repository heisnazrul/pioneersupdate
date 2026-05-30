<?php

namespace App\Http\Controllers\Team;

use App\Http\Controllers\Counsellor\ProfileController as CounsellorProfileController;
use Illuminate\View\View;

class ProfileController extends CounsellorProfileController
{
    public function edit(): View
    {
        return view('staff.profile.edit', [
            'user' => auth()->user(),
            'routePrefix' => 'team',
        ]);
    }
}

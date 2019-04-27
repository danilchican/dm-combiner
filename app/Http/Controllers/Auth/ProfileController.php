<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileInformationRequest;

class ProfileController extends Controller
{
    /**
     * Update Profile information about user.
     *
     * @param UpdateProfileInformationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfileInfo(UpdateProfileInformationRequest $request)
    {
        $name = $request->input('name');

        $user = \Auth::user();
        $user->setName($name);

        if($request->filled('password')) {
            $password = $request->input('password');
            $user->setPassword($password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Information about you is updated!');
    }
}

<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAccountInformationRequest;

class AccountController extends Controller
{
    /**
     * Show index page of the account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('account.index');
    }

    /**
     * @param UpdateAccountInformationRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateAccountInfo(UpdateAccountInformationRequest $request)
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

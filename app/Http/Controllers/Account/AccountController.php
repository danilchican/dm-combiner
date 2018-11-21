<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class AccountController extends Controller
{
    /**
     * Show index page of the account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function __invoke()
    {
        return view('account.index');
    }
}

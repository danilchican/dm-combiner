<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;

class DashboardController extends Controller
{
    private const USERS_PER_PAGE = 10;

    /**
     * Show index page of the dashboard.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \InvalidArgumentException
     */
    public function __invoke()
    {
        $users = User::paginate(self::USERS_PER_PAGE);
        return view('dashboard.home.index')->with(compact('users'));
    }
}

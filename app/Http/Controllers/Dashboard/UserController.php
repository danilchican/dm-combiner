<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    const USERS_PER_PAGE = 10;

    /**
     * Show User list Page.
     *
     * @throws \InvalidArgumentException
     *
     * @return $this
     */
    public function showUserListPage()
    {
        $users = User::paginate(self::USERS_PER_PAGE);
        return view('dashboard.users.index')->with(compact('users'));
    }

    public function viewUserPage($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('dashboard.users.view')->with(compact('user'));
    }
}

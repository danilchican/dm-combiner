<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    const USERS_PER_PAGE = 10;
    const USER_PROJECTS_PER_PAGE = 10;

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

    /**
     * View User information page.
     *
     * @param $id
     *
     * @return $this
     */
    public function viewUserPage($id)
    {
        $user = User::with('role')->findOrFail($id);
        $projects = $user->projects()->paginate(self::USER_PROJECTS_PER_PAGE);
        return view('dashboard.users.view')->with(compact(['user', 'projects']));
    }
}

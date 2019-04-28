<?php

namespace App\Http\Controllers\Account;

use App\Http\Requests\UpdateUserInfoRequest;
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
        $users = User::withCount('projects')->paginate(self::USERS_PER_PAGE);
        return view('account.users.index')->with(compact('users'));
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
        return view('account.users.view')->with(compact(['user', 'projects']));
    }

    /**
     * Update User information.
     *
     * @param UpdateUserInfoRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function updateUserInfo(UpdateUserInfoRequest $request)
    {
        $user = User::findOrFail($request->input('user-id'));

        $name = $request->input('name');
        $user->setName($name);

        if($request->filled('password')) {
            $password = $request->input('password');
            $user->setPassword($password);
        }

        $user->save();

        return redirect()->back()->with('success', 'Information about you is updated!');
    }
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function viewUserPage($id)
    {
        $user = User::with('role')->findOrFail($id);
        return view('dashboard.users.view')->with(compact('user'));
    }
}

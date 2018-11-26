<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    /**
     * Show projects list page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProjectsPage()
    {
        return view('account.projects.index');
    }

    /**
     * Show Create project page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showCreateProjectPage()
    {
        return view('account.projects.create');
    }
}

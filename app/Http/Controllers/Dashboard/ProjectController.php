<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProjectController extends Controller
{
    const PROJECTS_PER_PAGE = 10;

    /**
     * Show projects list page.
     *
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProjectsPage()
    {
        $projects = Project::paginate(self::PROJECTS_PER_PAGE);
        return view('dashboard.projects.index')->with('projects', $projects);
    }
}

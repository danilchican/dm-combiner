<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Services\Combiner\Contracts\CombinerContract;
use stdClass;

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
        return view('account.projects.create.index');
    }

    public function getFrameworksList(CombinerContract $combiner)
    {
        $frameworks = $combiner->getFrameworks();

        if($frameworks->success === true) {
            $combinedFrameworks = [];

            foreach($frameworks->result as $framework) {
                $object = new stdClass;
                $object->title = $framework;
                $object->commands = $this->getFrameworkCommands($framework, $combiner);

                $combinedFrameworks[] = $object;
            }
        }

        return response()->json($combinedFrameworks);
    }

    private function getFrameworkCommands(string $framework, CombinerContract $combiner) {
        $commands = $combiner->getFrameworkCommands($framework);

        if($commands->success === true) {
            return $commands->result;
        }

        return [];
    }
}

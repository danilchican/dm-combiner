<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Models\Project;
use App\Services\Combiner\Contracts\CombinerContract;
use stdClass;

class ProjectController extends Controller
{
    const PROJECTS_PER_PAGE = 10;

    /**
     * Show projects list page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProjectsPage()
    {
        $projects = \Auth::user()->projects()->paginate(self::PROJECTS_PER_PAGE);
        return view('account.projects.index')->with('projects', $projects);
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

    /**
     * Create project.
     *
     * @param CreateProjectRequest $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createProject(CreateProjectRequest $request)
    {
        $title = $request->input('title');
        $normalize = $request->input('normalize');
        $scale = $request->input('scale');
        $data_url = $request->input('data_url'); // TODO url of upload file
        $columns = $request->input('columns');
        $configuration = $request->input('configuration');
        $result = $request->input('result');

        $attributes = [
            'title'         => $title,
            'normalize'     => $normalize === 'true',
            'scale'         => $scale === 'true',
            'data_url'      => $data_url,
            'columns'       => json_encode($columns),
            'configuration' => json_encode($configuration),
            'result'        => $result,
        ];

        $project = new Project($attributes);
        \Auth::user()->projects()->save($project);

        return response()->json([
            'project' => $project,
            'message' => 'Project successfully created.',
        ]);
    }

    /**
     * Get frameworks list.
     *
     * @param CombinerContract $combiner
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getFrameworksList(CombinerContract $combiner)
    {
        $frameworks = $combiner->getFrameworks();

        if ($frameworks->success === true) {
            $combinedFrameworks = [];

            foreach ($frameworks->result as $framework) {
                $object = new stdClass;
                $object->title = $framework;
                $object->commands = $this->getFrameworkCommands($framework, $combiner);

                $combinedFrameworks[] = $object;
            }
        }

        return response()->json($combinedFrameworks);
    }

    private function getFrameworkCommands(string $framework, CombinerContract $combiner)
    {
        $commands = $combiner->getFrameworkCommands($framework);

        if ($commands->success === true) {
            return $commands->result;
        }

        return [];
    }
}

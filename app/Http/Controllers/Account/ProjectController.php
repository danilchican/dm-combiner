<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\UploadProjectDataRequest;
use App\Models\Project;
use App\Services\Combiner\Contracts\CombinerContract;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\File;
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
     * Upload project data
     *
     * @param UploadProjectDataRequest $request
     * @param CombinerContract         $combiner
     *
     * @param null                     $projectId
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadProjectData(UploadProjectDataRequest $request, CombinerContract $combiner, $projectId)
    {
        try {
            $project = Project::findOrFail($projectId);

            if ($request->has('data-file')) {
                $file = $request->file('data-file');

                $filename = $file->getClientOriginalName();
                $contents = File::get($file->getRealPath());
            } else {
                $filePath = $request->input('file-url');

                $filename = 'url-file_project-' . $projectId . '_'
                    . \Carbon\Carbon::now()->format('d-m-Y_H-i-s').'.csv';
                $contents = $this->getFileContentsByExternalUrl($filePath);
            }

            \Log::debug('Using ' . $filename . ' to upload data for project.');
            $response = $combiner->uploadFile($filename, $contents);
            \Log::debug('Combiner file upload response: ', [$response]);

            if ($response !== null) {
                if ($response->success === true) {
                    $project->setDataUrl($response->path);
                    $project->save();
                } else {
                    $project->delete();
                    \Log::error('Upload project file error.', [$response]);
                    return response()->json([
                        'message' => 'Upload project file error. See logs.',
                    ]);
                }

                return response()->json([
                    'message' => 'Project data successfully uploaded.',
                ]);
            }

            $project->delete();
            return response()->json([
                'message' => 'Looks lime something went wrong. Try again later.',
            ]);
        } catch (FileNotFoundException $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            return response()->json([
                'message' => 'File not found. Please select another file.',
            ], 400);
        } catch (ModelNotFoundException $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            return response()->json([
                'message' => 'Project not found. Please update page and try again.',
            ], 400);
        } catch (\Exception $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            return response()->json([
                'message' => 'Internal error. Try again later.',
            ], 400);
        }
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
        $columns = $request->input('columns');
        $configuration = $request->input('configuration');
        $result = $request->input('result');

        $attributes = [
            'title'         => $title,
            'normalize'     => $normalize === 'true',
            'scale'         => $scale === 'true',
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
        // TODO move to another layer
        $response = $combiner->getFrameworks();
        $combinedFrameworks = [];

        if ($response !== null && $response->success === true) {
            foreach ($response->frameworks as $framework) {
                $object = new stdClass;
                $object->title = $framework->name;
                $object->commands = [];

                foreach($framework->commands as $command) {
                    $commandObj = new stdClass;
                    $commandObj->title = $command;
                    $commandObj->framework = $framework->name;
                    $object->commands[] = $commandObj;
                }

                $combinedFrameworks[] = $object;
            }
        }

        return response()->json($combinedFrameworks);
    }

    /**
     * Get framework command options.
     *
     * @param CombinerContract $combiner
     * @param                  $framework
     * @param                  $command
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCommandOptions(CombinerContract $combiner, $framework, $command)
    {
        $response = $combiner->getCommandOptions($framework, $command);
        $options = [];

        if ($response !== null && $response->success === true) {
            foreach ($response->args as $option) {
                $object = new stdClass;
                $object->title = $option->name;
                $object->type = $option->type;

                $options[] = $object;
            }
        }

        return response()->json($options);
    }

    private function getFileContentsByExternalUrl(string $url)
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->get($url);

            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            return null;
        }
    }
}

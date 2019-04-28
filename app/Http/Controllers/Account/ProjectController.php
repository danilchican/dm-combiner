<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateProjectRequest;
use App\Http\Requests\RunProjectRequest;
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
     * @throws \InvalidArgumentException
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showProjectsPage()
    {
        $user = \Auth::user();

        $projects = $user->isAdministrator()
            ? Project::paginate(self::PROJECTS_PER_PAGE)
            : $user->projects()->paginate(self::PROJECTS_PER_PAGE);

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
     * View Project details page.
     *
     * @param integer $id
     *
     * @return $this
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function viewProjectDetailsPage($id)
    {
        $user = \Auth::user();
        $project = $user->isAdministrator()
            ? Project::with('user')->findOrFail($id)
            : $user->projects()->with('user')->findOrFail($id);

        $configuration = unserialize($project->getConfiguration());

        return view('account.projects.view')->with(compact(['project', 'configuration']));
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
                    . \Carbon\Carbon::now()->format('d-m-Y_H-i-s') . '.csv';
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
                    ], 400);
                }

                return response()->json([
                    'message' => 'Project data successfully uploaded.',
                ]);
            }

            $project->delete();
            return response()->json([
                'message' => 'Looks lime something went wrong. Try again later.',
            ], 400);
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

        $attributes = [
            'title'         => $title,
            'normalize'     => $normalize === 'true',
            'scale'         => $scale === 'true',
            'columns'       => serialize(array_map('intval', $columns)),
            'configuration' => serialize($this->prepareConfiguration($configuration)),
        ];

        $project = new Project($attributes);
        \Auth::user()->projects()->save($project);

        return response()->json([
            'project' => $project,
            'message' => 'Project successfully created.',
        ]);
    }

    /**
     * Run project.
     *
     * @param RunProjectRequest $request
     * @param CombinerContract  $combiner
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function runProject(RunProjectRequest $request, CombinerContract $combiner)
    {
        try {
            $projectId = $request->input('id');

            $project = \Auth::user()->projects()->findOrFail($projectId);
            $response = $combiner->executeAlgorithm($project);
            \Log::debug('Response from API: ', [$response]);

            if ($response !== null) {
                if ($response->success === true) {
                    $project->setResult(json_encode($response->result));
                    $project->setStatus('pending');
                    $project->save();
                } else {
                    \Log::error('Combiner project start execution error: ', [$response]);
                    return response()->json([
                        'message' => 'Project execution error. See logs. ' . $response->error,
                    ], 400);
                }

                return response()->json([
                    'message' => 'Project has been successfully run.',
                    'result'  => $project->getResult(),
                ]);
            }

            return response()->json([
                'message' => 'Looks lime something went wrong. Try again later.',
            ], 400);
        } catch (ModelNotFoundException $e) {
            \Log::error($e->getMessage(), $e->getTraceAsString());
            return response()->json([
                'message' => 'Project not found. Please update page and try again.',
            ], 400);
        }
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

                foreach ($framework->methods as $method) {
                    $commandObj = new stdClass;
                    $commandObj->title = $method;
                    $commandObj->framework = $framework->name;
                    $commandObj->options = [];

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
                $object->defaultValue = $option->default;
                $object->field = $this->getCommandOptionType($option->type);
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

    private function getCommandOptionType($type)
    {
        $types = [
            'int'   => 'number',
            'float' => 'number',
            'str'   => 'text',
            'bool'  => 'checkbox',
        ];

        if (array_key_exists($type, $types)) {
            return $types[$type];
        }

        return $types['str'];
    }

    private function prepareConfiguration($configuration)
    {
        $resultConfiguration = [];

        if (\is_array($configuration)) {
            foreach ($configuration as $commandConfig) {
                $tempConfig = [
                    'name'      => $commandConfig['title'],
                    'framework' => $commandConfig['framework'],
                ];

                $tempConfigOptions = [];

                if (array_key_exists('options', $commandConfig)) {
                    foreach ($commandConfig['options'] as $option) {
                        $optionValue = $this->getOptionValue($option);

                        if ($optionValue !== null) {
                            $tempConfigOptions[$option['title']] = $optionValue;
                        }
                    }

                    if (\count($tempConfigOptions) > 0) {
                        $tempConfig['params'] = $tempConfigOptions;
                    }
                }

                $resultConfiguration[] = $tempConfig;
            }
        }

        return $resultConfiguration;
    }

    private function getOptionValue($option)
    {
        if (\is_array($option)) {
            if (array_key_exists('value', $option)) {
                switch ($option['type']) {
                    case 'int':
                        return (int)$option['value'];
                    case 'float':
                        return (float)$option['value'];
                    case 'bool':
                        return $option['value'] === 'true';
                    case 'str':
                        return (string)$option['value'];
                    default:
                        return null;
                }
            }
        }

        return null;
    }
}

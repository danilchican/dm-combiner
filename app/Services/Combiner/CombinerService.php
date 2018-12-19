<?php

namespace App\Services\Combiner;

use App\Builders\CombinerEndpointBuilder;
use App\Models\Project;

class CombinerService extends AbstractCombiner
{
    public function __construct() { }

    /**
     * Execute algorithm.
     *
     * @param Project $project
     *
     * @return mixed
     */
    public function executeAlgorithm(Project $project)
    {
        $endpoint = CombinerEndpointBuilder::point()->to('/algorithm')->make();
        $options = $this->prepareExecuteAlgorithmOptions($project);

        $this->setConfiguration($endpoint, $options);
        return $this->executePostRequest();
    }

    /**
     * Get all frameworks.
     *
     * @return mixed
     */
    public function getFrameworks()
    {
        $endpoint = CombinerEndpointBuilder::point()->to('/frameworks')->make();
        $this->setConfiguration($endpoint);

        return $this->executeGetRequest();
    }

    /**
     * Get list of framework commands.
     *
     * @param string $framework
     *
     * @return mixed
     */
    public function getFrameworkCommands(string $framework)
    {
        $endpoint = CombinerEndpointBuilder::point()
            ->to('/commands/')
            ->to($framework)
            ->make();

        $this->setConfiguration($endpoint);
        return $this->executeGetRequest();
    }


    /**
     * Get framework command options.
     *
     * @param string $framework
     * @param string $command
     *
     * @return mixed
     */
    public function getCommandOptions(string $framework, string $command)
    {
        $endpoint = CombinerEndpointBuilder::point()
            ->to('/args/')
            ->to($framework)
            ->to('/' . $command)
            ->make();

        $this->setConfiguration($endpoint);
        return $this->executeGetRequest();
    }

    /**
     * Upload file for the project
     *
     * @param $filename
     * @param $content
     *
     * @return mixed
     */
    public function uploadFile($filename, $content)
    {
        $endpoint = CombinerEndpointBuilder::point()->to('/upload_file')->make();
        $options = [
            'multipart' => [
                [
                    'name'     => 'file',
                    'contents' => $content,
                    'filename' => $filename,
                ],
            ],
        ];

        $this->setConfiguration($endpoint, $options);
        return $this->executePostRequest();
    }

    private function prepareExecuteAlgorithmOptions(Project $project)
    {
        return [
            \GuzzleHttp\RequestOptions::JSON => [
                'config'   => [
                    'normalize'    => $project->getNormalize(),
                    'scale'        => $project->getScale(),
                    'file_url'     => $project->getDataUrl(),
                    'columns'      => unserialize($project->getCheckedColumns()),
                    'callback_url' => 'http://github.com/danilchican', // TODO
                ],
                'commands' => unserialize($project->getConfiguration()),
            ],
        ];
    }
}
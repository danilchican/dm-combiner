<?php

namespace App\Services\Combiner;

use App\Builders\CombinerEndpointBuilder;

class CombinerService extends AbstractCombiner
{
    public function __construct() { }

    /**
     * Execute algorithm.
     *
     * @return mixed
     */
    public function executeAlgorithm()
    {
        $this->prepareExecuteAlgorithmData();
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

    private function prepareExecuteAlgorithmData()
    {
        $endpoint = $this->prepareExecuteAlgorithmEndpoint();
        $options = $this->prepareExecuteAlgorithmOptions();

        $this->setConfiguration($endpoint, $options);
    }

    /**
     * Prepare endpoint for execution of algorithm.
     *
     * @return string|null
     */
    private function prepareExecuteAlgorithmEndpoint()
    {
        // TODO change URI to real
        return CombinerEndpointBuilder::point()->to('/process_json')->make();
    }

    private function prepareExecuteAlgorithmOptions()
    {
        return [
            \GuzzleHttp\RequestOptions::JSON => [
                // TODO json body for the request
            ],
        ];
    }
}
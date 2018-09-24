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
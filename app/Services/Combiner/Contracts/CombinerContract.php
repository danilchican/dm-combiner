<?php

namespace App\Services\Combiner\Contracts;

interface CombinerContract
{
    /**
     * Set configuration for the request.
     *
     * @param $endpoint
     * @param $options
     *
     * @return mixed
     */
    public function setConfiguration($endpoint, $options);

    /**
     * Execute algorithm.
     *
     * @return mixed
     */
    public function executeAlgorithm();

    /**
     * Get all frameworks.
     *
     * @return mixed
     */
    public function getFrameworks();

    /**
     * Get list of framework commands.
     *
     * @param string $framework
     *
     * @return mixed
     */
    public function getFrameworkCommands(string $framework);
}
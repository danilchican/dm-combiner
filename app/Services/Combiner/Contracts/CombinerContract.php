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

    /**
     * Get framework command options.
     *
     * @param string $framework
     * @param string $command
     *
     * @return mixed
     */
    public function getCommandOptions(string $framework, string $command);

    /**
     * Upload file for the project
     *
     * @param $filename
     * @param $content
     *
     * @return mixed
     */
    public function uploadFile($filename, $content);
}
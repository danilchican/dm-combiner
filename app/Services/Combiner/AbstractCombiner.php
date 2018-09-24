<?php

namespace App\Services\Combiner;

use App\Services\Combiner\Contracts\CombinerContract;

abstract class AbstractCombiner implements CombinerContract
{
    use CombinerTrait;

    protected const DELIMITER = '/';

    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var array
     */
    protected $options;

    /**
     * Set configuration for the request.
     *
     * @param $endpoint
     * @param $options
     */
    public function setConfiguration($endpoint, $options = null)
    {
        $this->endpoint = $endpoint;
        $this->options = $options;
    }
}
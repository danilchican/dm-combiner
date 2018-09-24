<?php

namespace App\Builders;

use App\Exceptions\CombinerConfigurationNotFoundException;

class CombinerEndpointBuilder extends AbstractEndpointBuilder
{
    private function __construct()
    {
        $this->root();
    }

    public function root()
    {
        // TODO handle exception
        $this->endpoint = $this->getCombinerUrl() . $this->getCombinerPrefix() . $this->getCombinerVersion();
    }

    public static function point()
    {
        return new CombinerEndpointBuilder();
    }

    /**
     * Get combiner URL endpoint.
     *
     * @return \Illuminate\Config\Repository|mixed
     * @throws CombinerConfigurationNotFoundException
     */
    private function getCombinerUrl()
    {
        $combinerUrl = config('api.combiner.url');

        if ($combinerUrl === null) {
            \Log::error('Combiner url is not set.');
            throw new CombinerConfigurationNotFoundException('Combiner url is not set.');
        }

        return $combinerUrl;
    }

    private function getCombinerPrefix()
    {
        $combinerPrefix = config('api.combiner.prefix');
        return $combinerPrefix === null ? '' : '/' . $combinerPrefix;
    }

    private function getCombinerVersion()
    {
        $combinerVersion = config('api.combiner.version');
        return $combinerVersion === null ? '' : '/' . $combinerVersion;
    }
}
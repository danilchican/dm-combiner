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
        try {
            $this->endpoint = config('app.combiner.mock.use')
                ? $this->getMockedCombinerUrl()
                : $this->getCombinerUrl() . $this->getCombinerPrefix() . $this->getCombinerVersion();
        } catch (CombinerConfigurationNotFoundException $e) {
            \Log::error($e->getTraceAsString());
        }
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

        if (empty($combinerUrl)) {
            \Log::error('Combiner url is not set.');
            throw new CombinerConfigurationNotFoundException('Combiner url is not set.');
        }

        return $combinerUrl;
    }

    private function getMockedCombinerUrl()
    {
        return config('app.combiner.mock.url');
    }

    private function getCombinerPrefix()
    {
        $combinerPrefix = config('api.combiner.prefix');
        return empty($combinerPrefix) ? '' : '/' . $combinerPrefix;
    }

    private function getCombinerVersion()
    {
        $combinerVersion = config('api.combiner.version');
        return empty($combinerVersion) ? '' : '/' . $combinerVersion;
    }
}
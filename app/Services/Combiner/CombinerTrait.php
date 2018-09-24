<?php

namespace App\Services\Combiner;

use GuzzleHttp\Exception\RequestException;

trait CombinerTrait
{
    /**
     * Execute POST request.
     *
     * @return mixed
     */
    public function executePostRequest()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->post($this->endpoint, $this->options);

            return \GuzzleHttp\json_decode($response->getBody())->data;
        } catch (RequestException $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }
}
<?php

namespace App\Service\Elasticsearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder as BaseClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;

readonly class ClientBuilder
{
    /**
     * @param string $elasticsearchEndpoint
     * @param string $elasticsearchApiKey
     */
    public function __construct(
        private string $elasticsearchEndpoint,
        private string $elasticsearchApiKey
    ) {
    }

    /**
     * @return Client
     * @throws AuthenticationException
     */
    public function buildClient(): Client
    {
//        dd($this->elasticsearchApiKey);
        return BaseClientBuilder::create()
            ->setHosts([$this->elasticsearchEndpoint])
            ->setApiKey($this->elasticsearchApiKey)
            ->build()
        ;
    }
}
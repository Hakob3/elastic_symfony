<?php

namespace App\Service\Elasticsearch;

use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\ClientBuilder as BaseClientBuilder;
use Elastic\Elasticsearch\Exception\AuthenticationException;

readonly class ClientBuilder
{
    /**
     * @param string $elasticsearchEndpoint
     */
    public function __construct(
        private string $elasticsearchEndpoint,
    ) {
    }

    /**
     * @return Client
     * @throws AuthenticationException
     */
    public function buildClient(): Client
    {
        return BaseClientBuilder::create()
            ->setHosts([$this->elasticsearchEndpoint])
            ->build()
        ;
    }
}
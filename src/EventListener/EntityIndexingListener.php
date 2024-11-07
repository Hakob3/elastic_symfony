<?php

namespace App\EventListener;

use App\Service\Elasticsearch\ElasticsearchFacade;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Event\PostRemoveEventArgs;
use Doctrine\ORM\Event\PostUpdateEventArgs;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use ReflectionException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;

final readonly class EntityIndexingListener
{
    /**
     * @param ElasticsearchFacade $elasticsearchFacade
     */
    public function __construct(private ElasticsearchFacade $elasticsearchFacade)
    {
    }

    /**
     * @param PostPersistEventArgs $event
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     * @throws ReflectionException
     * @throws ExceptionInterface
     */
    public function postPersist(PostPersistEventArgs $event): void
    {
        $this->elasticsearchFacade->indexingEntity($event->getObject());
    }

    /**
     * @param PostUpdateEventArgs $event
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws MissingParameterException
     * @throws ReflectionException
     * @throws ServerResponseException
     */
    public function postUpdate(PostUpdateEventArgs $event): void
    {
        $this->elasticsearchFacade->indexingEntity($event->getObject());
    }

    /**
     * @param PostRemoveEventArgs $event
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function postRemove(PostRemoveEventArgs $event): void
    {
        $this->elasticsearchFacade->deleteDocument($event->getObject());
    }
}

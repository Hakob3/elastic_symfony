<?php

namespace App\Service\Elasticsearch;

use App\Attributes\IndexingEntity;
use App\ElasticsearchDTO\Transformer\AbstractIndexingDTOTransformer;
use App\Service\ClassHelper;
use Doctrine\ORM\EntityManagerInterface;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use Exception;
use InvalidArgumentException;
use Http\Promise\Promise;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Config\ApiPlatform\ElasticsearchConfig;

readonly class ElasticsearchService
{
    private Client $client;

    /**
     * @param EntityManagerInterface $entityManager
     * @param ClientBuilder $clientBuilder
     * @param NormalizerInterface $objectNormalizer
     * @param ServiceLocator $transformersLocator
     * @throws AuthenticationException
     */
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ClientBuilder $clientBuilder,
        private NormalizerInterface $objectNormalizer,
        #[AutowireLocator(services: 'indexing.transformer', defaultIndexMethod: 'getTransformerEntityClass')]
        private ServiceLocator $transformersLocator
    ) {
        $this->client = $this->clientBuilder->buildClient();
    }

    /**
     * @param string $entityClass
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function createIndex(string $entityClass): Elasticsearch|Promise
    {
        return $this->client->indices()->create(
            [
                'index' => ClassHelper::getEntityIndexName($entityClass)
            ]
        );
    }

    /**
     * @param string $entityClass
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function deleteIndex(string $entityClass): Elasticsearch|Promise
    {
        return $this->client->indices()->delete(
            [
                'index' => ClassHelper::getEntityIndexName($entityClass)
            ]
        );
    }

    /**
     * @param object $entity
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function indexingEntity(object $entity): Elasticsearch|Promise
    {
        $transformer = $this->getEntityTransformer($entity);
        $dto = $transformer->transformFromObject($entity);

        $entityArray = $this->objectNormalizer->normalize(
            object: $dto,
            context: [AbstractObjectNormalizer::SKIP_NULL_VALUES]
        );

        return $this->client->index(
            [
                'index' => ClassHelper::getEntityIndexName($entity),
                'id' => (string)$entity->getId(),
                'body' => $entityArray,
            ]
        );
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function getDocument(object $entity)
    {
        $this->checkEntityConfiguredIndexing($entity);

        return $this->client->get(
            [
                'index' => ClassHelper::getEntityIndexName($entity),
                'id' => $entity->getId()
            ]
        )->asString();
    }

    public function searchDocument(string $queryString, string $entityClass)
    {
        $params = [
            'index' => ClassHelper::getEntityIndexName($entityClass),
//            'body'  => [
//                'query' => [
//                    'query_string' => [
//                        'query' => $queryString
//                    ]
//                ]
//            ]
        ];

        return $this->client->indices()->getMapping($params)->asArray();
    }

    /**
     * @throws ClientResponseException
     * @throws ServerResponseException
     * @throws MissingParameterException
     */
    public function deleteDocument(object $entity): Elasticsearch|Promise
    {
        $params = [
            'index' => ClassHelper::getEntityIndexName($entity),
            'id'    => $entity->getId()
        ];

        return $this->client->delete($params);
    }

    /**
     * @return Elasticsearch|Promise
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws ServerResponseException
     * @throws ReflectionException
     * @throws Exception
     */
    public function indexAllEntities(): Elasticsearch|Promise
    {
        $this->deleteAllDocuments();

        $params = ['body' => []];
        /** @var AbstractIndexingDTOTransformer $transformer */
        foreach ($this->transformersLocator->getIterator() as $transformer) {
            $reflectionClass = new ReflectionClass($transformer);
            $entityClass = $reflectionClass->getAttributes(IndexingEntity::class)[0]->getArguments()['entityClass'];
            $index = ClassHelper::getEntityIndexName($entityClass);
            foreach ($this->entityManager->getRepository($entityClass)->findAll() as $entity) {
                $params['body'][] = [
                    'index' => [
                        '_index' => $index,
                        '_id' => $entity->getId()
                    ]
                ];
                $entityArray = $this->objectNormalizer->normalize(
                    object: $transformer->transformFromObject($entity),
                    context: [AbstractObjectNormalizer::SKIP_NULL_VALUES]
                );
                $params['body'][] = $entityArray;
            }
        }

        return $this->client->bulk($params);
    }

    /**
     * @param object $entity
     * @return AbstractIndexingDTOTransformer
     */
    private function getEntityTransformer(object $entity): AbstractIndexingDTOTransformer
    {
        $this->checkEntityConfiguredIndexing($entity);

        return $this->transformersLocator->get(ClassHelper::getEntityIndexName($entity));
    }

    /**
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    private function deleteAllDocuments(): void
    {
        $params = [
            'index' => '_all',  // Указываем все индексы
            'body'  => [
                'query' => [
                    'match_all' => (object) []
                ]
            ]
        ];

        $this->client->deleteByQuery($params);
    }

    /**
     * @param object $entity
     * @return void
     */
    public function checkEntityConfiguredIndexing(object $entity): void
    {
        $entityClass = ClassHelper::getEntityIndexName($entity);
        if (!$this->transformersLocator->has($entityClass)) {
            throw new InvalidArgumentException(sprintf('The entity "%s" is not configured for indexing', $entityClass));
        }
    }
}
<?php

namespace App\Service\Elasticsearch;

use App\Elasticsearch\Transformer\AbstractIndexingDTOTransformer;
use App\Service\ClassHelper;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Transport\Exception\NotFoundException;
use InvalidArgumentException;
use ReflectionException;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ElasticsearchFacade
{
    private Client $client;

    private IndexBodyGenerator $indexBodyGenerator;

    /**
     * @param ClientBuilder $clientBuilder
     * @param NormalizerInterface $objectNormalizer
     * @param ServiceLocator $transformersLocator
     * @param IndexBodyGenerator $indexBodyGenerator
     * @throws AuthenticationException
     */
    public function __construct(
        private readonly ClientBuilder $clientBuilder,
        private readonly NormalizerInterface $objectNormalizer,
        #[AutowireLocator(services: 'indexing.transformer', defaultIndexMethod: 'getTransformerEntityClass')]
        private readonly ServiceLocator $transformersLocator,
        IndexBodyGenerator $indexBodyGenerator
    ) {
        $this->client = $this->clientBuilder->buildClient();
        $this->indexBodyGenerator = $indexBodyGenerator;
    }

    /**
     * @param IndexBodyGenerator $indexBodyGenerator
     * @return void
     */
    public function setIndexBodyGenerator(IndexBodyGenerator $indexBodyGenerator): void
    {
        $this->indexBodyGenerator = $indexBodyGenerator;
    }

    /**
     * @param string $entityClass
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ReflectionException
     * @throws ServerResponseException
     */
    public function createIndex(string $entityClass): void
    {
        if ($this->checkEntityConfiguredIndexing($entityClass)) {
            $requestBody = $this->indexBodyGenerator->generateIndexRequestBody(
                $this->getEntityTransformer($entityClass)::getTransformerDTOClass()
            );

            $this->client->indices()->create(
                [
                    'index' => ClassHelper::getEntityIndexName($entityClass),
                    'body' => $requestBody
                ]
            );
        }
    }

    /**
     * @param string $entityClass
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function deleteIndex(string $entityClass): void
    {
        $this->checkEntityConfiguredIndexing($entityClass, throwError: true);
        $this->exists($entityClass, throwError: true);

        $this->client->indices()->delete(
            [
                'index' => ClassHelper::getEntityIndexName($entityClass)
            ]
        );
    }

    /**
     * @param object $entity
     * @return void
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws MissingParameterException
     * @throws ReflectionException
     * @throws ServerResponseException
     */
    public function indexingEntity(object $entity): void
    {
        if ($this->checkEntityConfiguredIndexing($entity)) {
            if (!$this->exists($entity)) {
                $this->createIndex(get_class($entity));
            }

            $this->client->index(
                [
                    'index' => ClassHelper::getEntityIndexName($entity),
                    'id' => (string)$entity->getId(),
                    'body' => $this->normalizeEntity($entity)
                ]
            );
        }
    }

    /**
     * @param object $entity
     * @return void
     * @throws ClientResponseException
     * @throws ExceptionInterface
     * @throws MissingParameterException
     * @throws ReflectionException
     * @throws ServerResponseException
     */
    public function updateEntity(object $entity): void
    {
        if ($this->checkEntityConfiguredIndexing($entity) && $this->exists($entity)) {
            $this->client->update(
                [
                    'index' => ClassHelper::getEntityIndexName($entity),
                    'id' => (string)$entity->getId(),
                    'body' => ['doc' => $this->normalizeEntity($entity)],
                ]
            );
        }
    }

    /**
     * @throws ServerResponseException
     * @throws ClientResponseException
     * @throws MissingParameterException
     */
    public function getDocument(object $entity): ?string
    {
        $this->checkEntityConfiguredIndexing($entity, throwError: true);

        return $this->client->get(
            [
                'index' => ClassHelper::getEntityIndexName($entity),
                'id' => $entity->getId()
            ]
        )->asString();
    }

    /**
     * @param string $queryString
     * @param string $entityClass
     * @return array
     * @throws ClientResponseException
     * @throws ServerResponseException
     */
    public function searchDocument(string $queryString, string $entityClass): array
    {
        $this->checkEntityConfiguredIndexing($entityClass, throwError: true);

        $params = [
            'index' => ClassHelper::getEntityIndexName($entityClass),
            'body' => [
                'query' => [
                    'query_string' => [
                        'query' => $queryString
                    ]
                ]
            ]
        ];
        $responseArray = $this->client->search($params)->asArray();

        return array_map(static fn(array $hit) => $hit['_source'], $responseArray['hits']['hits']);
    }

    /**
     * @param object $entity
     * @return void
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    public function deleteDocument(object $entity): void
    {
        if ($this->checkEntityConfiguredIndexing($entity) && $this->exists($entity)) {
            $params = [
                'index' => ClassHelper::getEntityIndexName($entity),
                'id' => $entity->getId()
            ];

            $this->client->delete($params);
        }
    }

    /**
     * @param object $entity
     * @return array
     * @throws ReflectionException
     * @throws ExceptionInterface
     */
    private function normalizeEntity(object $entity): array
    {
        $transformer = $this->getEntityTransformer($entity);
        $dto = $transformer->transformFromObject($entity);
        $properties = array_keys($this->indexBodyGenerator->generateMappings(get_class($dto))['properties'] ?? []);
        $context = [AbstractObjectNormalizer::SKIP_NULL_VALUES];
        if ($properties) {
            $context['attributes'] = $properties;
        }

        return $this->objectNormalizer->normalize(
            object: $dto,
            context: $context
        );
    }

    /**
     * @param object|string $entity
     * @return AbstractIndexingDTOTransformer
     */
    private function getEntityTransformer(object|string $entity): AbstractIndexingDTOTransformer
    {
        $this->checkEntityConfiguredIndexing($entity, throwError: true);

        return $this->transformersLocator->get(ClassHelper::getEntityIndexName($entity));
    }

    /**
     * @param object|string $entity
     * @param bool $throwError
     * @return bool
     */
    public function checkEntityConfiguredIndexing(object|string $entity, bool $throwError = false): bool
    {
        $entityClass = ClassHelper::getEntityIndexName($entity);
        if ($this->transformersLocator->has($entityClass)) {
            return true;
        }

        if ($throwError) {
            throw new InvalidArgumentException(sprintf('The entity "%s" is not configured for indexing', $entityClass));
        }

        return false;
    }

    /**
     * @param string|object $entityClass
     * @param bool $throwError
     * @return bool
     * @throws ClientResponseException
     * @throws MissingParameterException
     * @throws ServerResponseException
     */
    private function exists(object|string $entityClass, bool $throwError = false): bool
    {
        $entityClass = is_object($entityClass) ? get_class($entityClass) : $entityClass;

        $indexExists = $this->client->indices()->exists(
                [
                    'index' => ClassHelper::getEntityIndexName($entityClass)
                ]
            )->getStatusCode() === Response::HTTP_OK;

        if ($indexExists) {
            return true;
        }

        if ($throwError) {
            throw new NotFoundException();
        }

        return false;
    }
}
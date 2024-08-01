<?php

namespace App\Service\Elasticsearch;

use App\Attributes\IndexingEntity;
use App\ElasticsearchDTO\Transformer\AbstractDTOTransformer;
use App\ElasticsearchDTO\Transformer\DTOTransformerInterface;
use App\Service\ClassHelper;
use Elastic\Elasticsearch\Client;
use Elastic\Elasticsearch\Exception\AuthenticationException;
use Elastic\Elasticsearch\Exception\ClientResponseException;
use Elastic\Elasticsearch\Exception\MissingParameterException;
use Elastic\Elasticsearch\Exception\ServerResponseException;
use Elastic\Elasticsearch\Response\Elasticsearch;
use http\Exception\InvalidArgumentException;
use Http\Promise\Promise;
use Symfony\Bundle\MakerBundle\Str;
use Symfony\Component\DependencyInjection\Attribute\AutowireLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

readonly class IndexingService
{
    private Client $client;

    /**
     * @param ClientBuilder $clientBuilder
     * @param NormalizerInterface $objectNormalizer
     * @param ServiceLocator $transformersLocator
     * @throws AuthenticationException
     */
    public function __construct(
        private ClientBuilder    $clientBuilder,
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
    public function createIndexByEntity(string $entityClass): Elasticsearch|Promise
    {
//        dd($this->client);
        return $this->client->indices()->create(
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
                'body' => $entityArray
            ]
        );
    }

    public function indexAllEntities()
    {

    }

    /**
     * @param object $entity
     * @return AbstractDTOTransformer
     */
    public function getEntityTransformer(object $entity): AbstractDTOTransformer
    {
        $entityClass = ClassHelper::getEntityIndexName($entity);
        if (!$this->transformersLocator->has($entityClass)){
            throw new InvalidArgumentException('The entity is not configured for indexing');
        }

        return $this->transformersLocator->get(ClassHelper::getEntityIndexName($entity));
    }
}
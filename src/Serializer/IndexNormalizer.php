<?php

namespace App\Serializer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class IndexNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
        private UrlGeneratorInterface $router,
    ) {
    }

    public function normalize($topic, ?string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($topic, $format, $context);

        // Here, add, edit, or delete some data:
        $data['href']['self'] = $this->router->generate('topic_show', [
            'id' => $topic->getId(),
        ], UrlGeneratorInterface::ABSOLUTE_URL);

        return $data;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof Topic;
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            Topic::class => true,
        ];
    }
}
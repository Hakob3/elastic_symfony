<?php

namespace App\Service\Elasticsearch;

use App\Elasticsearch\Index\Mapping;
use App\Elasticsearch\Index\AnalysisSettings;
use ReflectionClass;
use ReflectionException;

readonly class IndexBodyGenerator
{
    /**
     * @throws ReflectionException
     */
    public function generateIndexRequestBody(string $dto): array
    {
        $analysisSettings = $this->generateAnalysisSettings($dto);
        $mappings = $this->generateMappings($dto);

        return array_filter([
            'settings' => array_filter([
                'analysis' => $analysisSettings
            ]),
            'mappings' => $mappings
        ]);
    }

    /**
     * @param class-string $dto
     * @return array|null
     * @throws ReflectionException
     */
    public function generateAnalysisSettings(string $dto): ?array
    {
        $reflectionClass = new ReflectionClass($dto);
        $attribute = $reflectionClass->getAttributes(name: AnalysisSettings::class)[0] ?? null;

        return $attribute?->newInstance()->getConfiguration();
    }

    /**
     * @param class-string $dto
     * @return array|null
     * @throws ReflectionException
     */
    public function generateMappings(string $dto): ?array
    {
        $reflectionClass = new ReflectionClass($dto);
        $properties = $reflectionClass->getProperties();
        $mappings = [];

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(Mapping::class);

            foreach ($attributes as $attribute) {
                /** @var Mapping $mapping */
                $mapping = $attribute->newInstance();
                $mappings['properties'][$property->getName()] = $mapping->getConfiguration();
            }
        }

        return $mappings;
    }
}

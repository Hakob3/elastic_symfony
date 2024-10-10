<?php

namespace App\Service\Elasticsearch;

use App\Elasticsearch\Mapping\ElasticsearchMapping;
use ReflectionClass;
use ReflectionException;

class ElasticsearchMappingGenerator
{
    /**
     * @throws ReflectionException
     */
    public static function generateMapping(string $dto): array
    {
        $reflectionClass = new ReflectionClass($dto);
        $properties = $reflectionClass->getProperties();

        $mapping = [];

        foreach ($properties as $property) {
            $attributes = $property->getAttributes(ElasticsearchMapping::class);

            foreach ($attributes as $attribute) {
                /** @var ElasticsearchMapping $elasticsearchMapping */
                $elasticsearchMapping = $attribute->newInstance();

                $mapping['properties'][$property->getName()] = [
                    'type' => $elasticsearchMapping->getType()
                ];

                $analyzer = $elasticsearchMapping->getAnalyzer();
                if ($analyzer) {
                    $mapping['properties'][$property->getName()]['analyzer'] = $analyzer->getConfiguration();
                }
            }
        }

        return $mapping;
    }
}

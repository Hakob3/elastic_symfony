<?php

namespace App\Service\Elasticsearch;

use App\Elasticsearch\Index\Analysis\Analyzer;
use App\Elasticsearch\Index\Analysis\CharFilter;
use App\Elasticsearch\Index\Analysis\TokenFilter;
use App\Elasticsearch\Index\Analysis\Tokenizer;
use App\Elasticsearch\Index\ElasticsearchMapping;
use App\Elasticsearch\Index\IndexSettings;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class ElasticsearchMappingGenerator
{
    public function __construct(private readonly ObjectNormalizer $normalizer)
    {
    }

    /**
     * @throws ReflectionException
     */
    public static function generateMapping(string $dto): array
    {
        $reflectionClass = new ReflectionClass($dto);
        $properties = $reflectionClass->getProperties();
        $settingsAttribute = $reflectionClass->getAttributes(name: IndexSettings::class)[0]->getArguments();
        $normalize = new ObjectNormalizer(nameConverter: new CamelCaseToSnakeCaseNameConverter());
        $mapping = [];
        $json = json_encode($settingsAttribute);

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

<?php

namespace App\Elasticsearch\Transformer;

use App\Attributes\IndexingEntity;
use App\Service\ClassHelper;
use ReflectionClass;

abstract class AbstractIndexingDTOTransformer implements IndexingDTOTransformerInterface
{
    /**
     * @return string
     */
    public static function getTransformerEntityClass(): string
    {
        $reflection = new ReflectionClass(static::class);

        return ClassHelper::getEntityIndexName(
            $reflection->getAttributes(IndexingEntity::class)[0]->getArguments()['entityClass']
        );
    }

    /**
     * @return string
     */
    public static function getTransformerDTOClass(): string
    {
        $reflection = new ReflectionClass(static::class);

        return $reflection->getAttributes(IndexingEntity::class)[0]->getArguments()['dto'];
    }

    public function transformFromObjects(iterable $objects): iterable
    {
        $data = [];

        foreach ($objects as $object) {
            $data[] = $this->transformFromObject($object);
        }

        return $data;
    }
}

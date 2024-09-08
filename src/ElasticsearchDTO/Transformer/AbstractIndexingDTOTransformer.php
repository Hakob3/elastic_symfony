<?php

namespace App\ElasticsearchDTO\Transformer;

use App\Attributes\IndexingEntity;
use App\Service\ClassHelper;
use ReflectionClass;

abstract class AbstractDTOTransformer implements DTOTransformerInterface
{
    /**
     * @return string
     */
    public static function getTransformerEntityClass(): string
    {
        $reflection = new ReflectionClass(static::class);
//        dd(ClassHelper::getEntityIndexName(
//            $reflection->getAttributes(IndexingEntity::class)[0]->getArguments()['entityClass']
//        ));

        return ClassHelper::getEntityIndexName(
            $reflection->getAttributes(IndexingEntity::class)[0]->getArguments()['entityClass']
        );
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

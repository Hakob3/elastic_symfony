<?php

namespace App\ElasticsearchDTO\Transformer;

interface DTOTransformerInterface
{
    /**
     * @param $object
     * @return mixed
     */
    public function transformFromObject($object): mixed;

    /**
     * @param iterable $objects
     * @return iterable
     */
    public function transformFromObjects(iterable $objects): iterable;
}

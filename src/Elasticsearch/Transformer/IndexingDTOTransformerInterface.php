<?php

namespace App\Elasticsearch\Transformer;

interface IndexingDTOTransformerInterface
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

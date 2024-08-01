<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class IndexingEntity
{
    /**
     * @param string $entityClass
     */
    public function __construct(private string $entityClass)
    {
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }
}
<?php

namespace App\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class IndexingEntity
{
    /**
     * @param string $entityClass
     * @param string $dto
     */
    public function __construct(private string $entityClass, private string $dto)
    {
    }

    /**
     * @return string
     */
    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    /**
     * @return string
     */
    public function getDto(): string
    {
        return $this->dto;
    }
}
<?php

namespace App\Elasticsearch\Transformer;

use App\Attributes\IndexingEntity;
use App\Elasticsearch\DTO\ArticleCategoryDTO;
use App\Entity\ArticleCategory;

class ArticleCategoryDTOTransformer extends AbstractIndexingDTOTransformer
{
    /**
     * @param ArticleCategory $object
     * @return ArticleCategoryDTO
     */
    public function transformFromObject($object): ArticleCategoryDTO
    {
        $dto = new ArticleCategoryDTO();

        $dto->id = $object->getId();
        $dto->name = $object->getName();

        return $dto;
    }
}
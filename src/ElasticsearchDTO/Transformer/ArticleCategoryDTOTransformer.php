<?php

namespace App\ElasticsearchDTO\Transformer;

use App\Attributes\IndexingEntity;
use App\ElasticsearchDTO\DTO\ArticleCategoryDTO;
use App\Entity\ArticleCategory;

class ArticleCategoryDTOTransformer extends AbstractDTOTransformer
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
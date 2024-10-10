<?php

namespace App\Elasticsearch\Transformer;

use App\Attributes\IndexingEntity;
use App\Elasticsearch\DTO\ArticleDTO;
use App\Entity\Article;

#[IndexingEntity(
    entityClass: Article::class,
    dto: ArticleDTO::class
)]
class ArticleDTOTransformer extends AbstractIndexingDTOTransformer
{
    /**
     * @param ArticleCategoryDTOTransformer $articleCategoryDTOTransformer
     */
    public function __construct(
        private readonly ArticleCategoryDTOTransformer $articleCategoryDTOTransformer
    ) {
    }

    /**
     * @param Article $object
     * @return ArticleDTO
     */
    public function transformFromObject($object): ArticleDTO
    {
        $dto = new ArticleDTO();

        $dto->id = $object->getId();
        $dto->title = $object->getTitle();
        $dto->content = $object->getContent();
        $dto->articleCategory = $this->articleCategoryDTOTransformer->transformFromObject(
            $object->getArticleCategory()
        );

        return $dto;
    }
}
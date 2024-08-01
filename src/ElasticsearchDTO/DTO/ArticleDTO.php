<?php

namespace App\ElasticsearchDTO\DTO;

use JMS\Serializer\Annotation\Type;

class ArticleDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    public ?string $title = null;

    #[Type("text")]
    public ?string $content = null;

    #[Type(ArticleCategoryDTO::class)]
    public ?ArticleCategoryDTO $articleCategory = null;
}
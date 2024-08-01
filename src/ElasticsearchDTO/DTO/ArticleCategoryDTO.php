<?php

namespace App\ElasticsearchDTO\DTO;

use JMS\Serializer\Annotation\Type;

class ArticleCategoryDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    public ?string $name = null;
}
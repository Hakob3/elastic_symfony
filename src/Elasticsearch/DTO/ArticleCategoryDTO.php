<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Index\Mapping;
use JMS\Serializer\Annotation\Type;

class ArticleCategoryDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    #[Mapping(type: Mapping::TYPE_TEXT)]
    public ?string $name = null;
}
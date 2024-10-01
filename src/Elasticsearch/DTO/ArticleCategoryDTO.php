<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Mapping\ElasticsearchMapping;
use JMS\Serializer\Annotation\Type;

class ArticleCategoryDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    #[ElasticsearchMapping(type: ElasticsearchMapping::TYPE_TEXT)]
    public ?string $name = null;
}
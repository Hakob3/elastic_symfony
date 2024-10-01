<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Analyzer\Analyzer;
use App\Elasticsearch\Analyzer\CustomAnalyzer;
use App\Elasticsearch\Mapping\ElasticsearchMapping;
use JMS\Serializer\Annotation\Type;

class ArticleDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    #[ElasticsearchMapping(type: ElasticsearchMapping::TYPE_TEXT, analyzer: new CustomAnalyzer(type: Analyzer::TYPE_STANDARD))]
    public ?string $title = null;

    #[Type("text")]
    #[ElasticsearchMapping(type: ElasticsearchMapping::TYPE_TEXT)]
    public ?string $content = null;

    #[Type(ArticleCategoryDTO::class)]
    public ?ArticleCategoryDTO $articleCategory = null;
}
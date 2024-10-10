<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Mapping\Analyzer;
use App\Elasticsearch\Mapping\ElasticsearchMapping;
use App\Elasticsearch\Mapping\Tokenizer;
use JMS\Serializer\Annotation\Type;

class ArticleDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    #[ElasticsearchMapping(
        type: ElasticsearchMapping::TYPE_TEXT,
        analyzer: new Analyzer(
            name: 'custom_analyzer',
            type: Analyzer::TYPE_STANDARD,
            tokenizer: new Tokenizer(name: 'custom_tokenizer', type: Tokenizer::TYPE_STANDARD)
        )
    )]
    public ?string $title = null;

    #[Type("text")]
    #[ElasticsearchMapping(type: ElasticsearchMapping::TYPE_TEXT)]
    public ?string $content = null;

    #[Type(ArticleCategoryDTO::class)]
    public ?ArticleCategoryDTO $articleCategory = null;
}
<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Mapping\Analyzer;
use App\Elasticsearch\Mapping\CharFilter;
use App\Elasticsearch\Mapping\ElasticsearchMapping;
use App\Elasticsearch\Mapping\IndexSettings;
use App\Elasticsearch\Mapping\TokenFilter;
use App\Elasticsearch\Mapping\Tokenizer;
use JMS\Serializer\Annotation\Type;

#[IndexSettings(
    analyzer: [
        new Analyzer(
            type: Analyzer::TYPE_CUSTOM,
            name: 'my_analyzer',
            filter: [
                TokenFilter::TYPE_LOWERCASE
            ],
            charFilter: [
                'my_char_filter'
            ]
        )
    ],
    charFilter: [
        new CharFilter(
            type: CharFilter::TYPE_PATTERN_REPLACE,
            name: "my_char_filter",
            settings: [
                "pattern" => "(?<=\\p{Lower})(?=\\p{Upper})",
                "replacement" => " "
            ]
        )
    ]
)]
class ArticleDTO
{
    #[Type("integer")]
    public ?int $id = null;

    #[Type("string")]
    #[ElasticsearchMapping(
        type: ElasticsearchMapping::TYPE_TEXT,
        analyzer: "my_analyzer",
    )]
    public ?string $title = null;

    #[Type("text")]
    #[ElasticsearchMapping(type: ElasticsearchMapping::TYPE_TEXT)]
    public ?string $content = null;

    #[Type(ArticleCategoryDTO::class)]
    public ?ArticleCategoryDTO $articleCategory = null;
}
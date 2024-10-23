<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Index\Analysis\Analyzer;
use App\Elasticsearch\Index\Analysis\CharFilter;
use App\Elasticsearch\Index\Analysis\TokenFilter;
use App\Elasticsearch\Index\Analysis\Tokenizer;
use App\Elasticsearch\Index\ElasticsearchMapping;
use App\Elasticsearch\Index\IndexSettings;
use JMS\Serializer\Annotation\Type;

#[IndexSettings(
    analyzer: [
        new Analyzer(
            type: Analyzer::TYPE_CUSTOM,
            name: 'my_analyzer',
            tokenizer: Tokenizer::TYPE_WHITESPACE,
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
    private const CUSTOM_ANALYZER = "custom_analyzer";

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
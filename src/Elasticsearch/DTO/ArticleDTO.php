<?php

namespace App\Elasticsearch\DTO;

use App\Elasticsearch\Index\Analysis\Analyzer;
use App\Elasticsearch\Index\Analysis\CharFilter;
use App\Elasticsearch\Index\Analysis\TokenFilter;
use App\Elasticsearch\Index\Analysis\Tokenizer;
use App\Elasticsearch\Index\Mapping;
use App\Elasticsearch\Index\AnalysisSettings;
use JMS\Serializer\Annotation\Type;

#[AnalysisSettings(
    analyzer: [
        new Analyzer(
            type: Analyzer::TYPE_CUSTOM,
            tokenizer: Tokenizer::TYPE_STANDARD,
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
    #[Mapping(type: Mapping::TYPE_KEYWORD)]
    public ?int $id = null;

    #[Type("string")]
    #[Mapping(
        type: Mapping::TYPE_TEXT,
        analyzer: "my_analyzer",
    )]
    public ?string $title = null;

    #[Type("text")]
    #[Mapping(type: Mapping::TYPE_TEXT)]
    public ?string $content = null;

    #[Type(ArticleCategoryDTO::class)]
    public ?ArticleCategoryDTO $articleCategory = null;
}
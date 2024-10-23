<?php

namespace App\Elasticsearch\Index;

use App\Elasticsearch\Index\Analysis\Analyzer;
use App\Elasticsearch\Index\Analysis\CharFilter;
use App\Elasticsearch\Index\Analysis\TokenFilter;
use App\Elasticsearch\Index\Analysis\Tokenizer;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class IndexSettings
{
    /**
     * @param Analyzer[]|null $analyzer
     * @param Tokenizer[]|null $tokenizer
     * @param CharFilter[]|null $charFilter
     * @param TokenFilter[]|null $filter
     */
    public function __construct(
        private ?array $analyzer = null,
        private ?array $tokenizer = null,
        private ?array $charFilter = null,
        private ?array $filter = null,
        private ?array $settings = null,
    ) {}

    /**
     * @return Analyzer[]|null
     */
    public function getAnalyzer(): ?array
    {
        return $this->analyzer;
    }

    /**
     * @return Tokenizer[]|null
     */
    public function getTokenizer(): ?array
    {
        return $this->tokenizer;
    }

    /**
     * @return CharFilter[]|null
     */
    public function getCharFilter(): ?array
    {
        return $this->charFilter;
    }

    /**
     * @return TokenFilter[]|null
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }
}

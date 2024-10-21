<?php

namespace App\Elasticsearch\Mapping;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class IndexSettings
{
    /**
     * @param Analyzer[]|null $analyzer
     * @param Tokenizer[]|null $tokenizer
     * @param CharFilter[]|null $charFilter
     * @param TokenFilter[]|null $tokenFilter
     */
    public function __construct(
        private ?array $analyzer = null,
        private ?array $tokenizer = null,
        private ?array $charFilter = null,
        private ?array $tokenFilter = null,
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
    public function getTokenFilter(): ?array
    {
        return $this->tokenFilter;
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }
}

<?php

namespace App\Elasticsearch\Index\Analysis;

use JsonSerializable;

class Analyzer implements AnalysisInterface, JsonSerializable
{
    public const TYPE_CUSTOM = 'custom';
    public const TYPE_STANDARD = 'standard';
    public const TYPE_SIMPLE = 'simple';
    public const TYPE_WHITESPACE = 'whitespace';
    public const TYPE_STOP = 'stop';
    public const TYPE_PATTERN = 'pattern';
    public const TYPE_KEYWORD = 'keyword';
    public const TYPE_LANGUAGE = 'language';
    public const TYPE_FINGERPRINT = 'fingerprint';

    /**
     * @param string $type
     * @param string|null $name
     * @param string|null $tokenizer
     * @param string[]|null $filter
     * @param string[]|null $charFilter
     * @param array|null $settings
     */
    public function __construct(
        private readonly string $type,
        private readonly ?string $name = null,
        private readonly ?string $tokenizer = null,
        private readonly ?array $filter = [],
        private readonly ?array $charFilter = [],
        private readonly ?array $settings = []
    ) {
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getTokenizer(): ?string
    {
        return $this->tokenizer;
    }

    /**
     * @return string[]|null
     */
    public function getFilter(): ?array
    {
        return $this->filter;
    }

    /**
     * @return string[]|null
     */
    public function getCharFilter(): ?array
    {
        return $this->charFilter;
    }

    /**
     * @return array|null
     */
    public function getSettings(): ?array
    {
        return $this->settings;
    }

    /**
     * @return array|null
     */
    public function jsonSerialize(): ?array
    {
        if ($this->name === null) {
            return null;
        }

        return array_merge([
            'type' => $this->type,
            'tokenizer' => $this->tokenizer,
            'filter' => $this->filter,
            'char_filter' => $this->charFilter
        ], $this->settings);
    }
}

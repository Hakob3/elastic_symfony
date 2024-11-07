<?php

namespace App\Elasticsearch\Index\Analysis;

use JsonSerializable;

class Analyzer implements AnalysisInterface
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
     * @param string $tokenizer
     * @param string|null $name
     * @param array|null $filter
     * @param array|null $charFilter
     * @param array|null $settings
     */
    public function __construct(
        private readonly string $type,
        private readonly string $tokenizer,
        private readonly ?string $name = null,
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
     * @return array|null
     */
    public function getConfiguration(): ?array
    {
        if ($this->name === null) {
            return null;
        }

        return array_filter(array_merge([
            'type' => $this->type,
            'tokenizer' => $this->tokenizer,
            'filter' => $this->filter,
            'char_filter' => $this->charFilter
        ], $this->settings));
    }
}

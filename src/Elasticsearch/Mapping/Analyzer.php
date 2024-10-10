<?php

namespace App\Elasticsearch\Mapping;

class Analyzer
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

    private string $name;
    private string $type;
    private Tokenizer $tokenizer;
    private array $filters;

    public function __construct(string $name, string $type, Tokenizer $tokenizer, array $filters = [])
    {
        $this->name = $name;
        $this->type = $type;
        $this->tokenizer = $tokenizer;
        $this->filters = $filters;
    }

    public function getConfiguration(): array
    {
        return [
            $this->name => [
                'type' => $this->type,
                'tokenizer' => $this->tokenizer->getConfiguration(),
                'filter' => $this->filters
            ]
        ];
    }
}

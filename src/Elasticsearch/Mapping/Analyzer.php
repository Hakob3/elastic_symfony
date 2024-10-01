<?php

namespace App\Elasticsearch\Mapping;

class Analyzer
{
    public const TYPE_STANDARD = 'standard';
    public const TYPE_CUSTOM = 'custom';
    public const TYPE_WHITESPACE = 'whitespace';
    public const TYPE_KEYWORD = 'keyword';
    public const TYPE_SIMPLE = 'simple';
    public const TYPE_STOP = 'stop';
    public const TYPE_PATTERN = 'pattern';
    public const TYPE_LANGUAGE = 'language';
    public const TYPE_FINGERPRINT = 'fingerprint';

    private string $type;
    private array $settings;

    public function __construct(string $type, array $settings = [])
    {
        $this->type = $type;
        $this->settings = $settings;
    }

    public function toArray(): array
    {
        return array_merge(['type' => $this->type], $this->settings);
    }
}

<?php

namespace App\Elasticsearch\Index;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
class Mapping
{
    public const TYPE_TEXT = 'text';
    public const TYPE_KEYWORD = 'keyword';
    public const TYPE_LONG = 'long';
    public const TYPE_INTEGER = 'integer';
    public const TYPE_FLOAT = 'float';
    public const TYPE_DOUBLE = 'double';
    public const TYPE_BOOLEAN = 'boolean';
    public const TYPE_DATE = 'date';
    public const TYPE_BINARY = 'binary';
    public const TYPE_GEO_POINT = 'geo_point';
    public const TYPE_GEO_SHAPE = 'geo_shape';
    public const TYPE_IP = 'ip';
    public const TYPE_COMPLETION = 'completion';
    public const TYPE_OBJECT = 'object';
    public const TYPE_NESTED = 'nested';

    /**
     * @param string $type
     * @param string|null $analyzer
     * @param array $settings
     */
    public function __construct(
        private readonly string $type,
        private readonly ?string $analyzer = null,
        private readonly array $settings = []
    ) {}

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return array|null
     */
    public function getConfiguration(): ?array
    {
        return array_filter(array_merge([
            'type' => $this->type,
            'analyzer' => $this->analyzer,
        ], $this->settings ?? []));
    }
}

<?php

namespace App\Elasticsearch\Mapping;

use App\Elasticsearch\Analyzer\Analyzer;
use Attribute;

#[Attribute]
class ElasticsearchMapping
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
     * @param Analyzer|null $analyzer
     */
    public function __construct(
        public string $type,
        public ?Analyzer $analyzer = null
    ) {
    }
}

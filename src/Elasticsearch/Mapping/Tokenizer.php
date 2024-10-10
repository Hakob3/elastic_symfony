<?php

namespace App\Elasticsearch\Mapping;

class Tokenizer
{
    public const TYPE_STANDARD = 'standard';
    public const TYPE_SIMPLE = 'simple';
    public const TYPE_WHITESPACE = 'whitespace';
    public const TYPE_KEYWORD = 'keyword';
    public const TYPE_PATTERN = 'pattern';
    public const TYPE_UAX_URL_EMAIL = 'uax_url_email';
    public const TYPE_CLASSIC = 'classic';
    public const TYPE_NGRAM = 'ngram';
    public const TYPE_EDGE_NGRAM = 'edge_ngram';

    public const TYPE_PATH_HIERARCHY = 'path_hierarchy';

    public const TYPE_CHAR_GROUP = 'char_group';

    private string $name;

    private string $type;

    /**
     * @param string $name
     * @param string $type
     */
    public function __construct(string $name, string $type)
    {
        $this->name = $name;
        $this->type = $type;
    }

    /**
     * @return array
     */
    public function getConfiguration(): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type
        ];
    }
}

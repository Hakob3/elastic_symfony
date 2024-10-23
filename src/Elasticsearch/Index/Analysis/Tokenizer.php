<?php

namespace App\Elasticsearch\Index\Analysis;

use JsonSerializable;

class Tokenizer implements AnalysisInterface, JsonSerializable
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

    /**
     * @param string $type
     * @param string|null $name
     * @param array|null $settings
     */
    public function __construct(
        private readonly string $type,
        private readonly ?string $name = null,
        private readonly ?array $settings = null
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
    public function jsonSerialize(): ?array
    {
        if ($this->name === null) {
            return null;
        }

        return [
            $this->name => array_merge([
                'type' => $this->type,
            ], $this->settings ?? []),
        ];
    }
}

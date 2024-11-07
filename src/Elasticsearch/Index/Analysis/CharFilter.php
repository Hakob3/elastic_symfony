<?php

namespace App\Elasticsearch\Index\Analysis;

use JsonSerializable;

class CharFilter implements AnalysisInterface
{
    public const TYPE_HTML_STRIP = 'html_strip';
    public const TYPE_MAPPING = 'mapping';
    public const TYPE_PATTERN_REPLACE = 'pattern_replace';

    /**
     * @param string $type
     * @param string|null $name
     * @param array|null $settings
     */
    public function __construct(
        public readonly string $type,
        public readonly ?string $name = null,
        public readonly ?array $settings = null
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
        ], $this->settings ?? []));
    }
}

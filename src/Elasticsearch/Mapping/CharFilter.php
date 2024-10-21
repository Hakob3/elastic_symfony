<?php

namespace App\Elasticsearch\Mapping;

class CharFilter
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
     * @return array
     */
    public function getConfiguration(): array
    {
        return [
            $this->name => array_merge([
                'type' => $this->type,
            ], $this->settings ?? []),
        ];
    }
}

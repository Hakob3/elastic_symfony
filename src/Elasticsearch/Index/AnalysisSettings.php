<?php

namespace App\Elasticsearch\Index;

use App\Elasticsearch\Index\Analysis\AnalysisInterface;
use App\Elasticsearch\Index\Analysis\Analyzer;
use App\Elasticsearch\Index\Analysis\CharFilter;
use App\Elasticsearch\Index\Analysis\TokenFilter;
use App\Elasticsearch\Index\Analysis\Tokenizer;
use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
readonly class AnalysisSettings
{
    /**
     * @param Analyzer[]|null $analyzer
     * @param Tokenizer[]|null $tokenizer
     * @param CharFilter[]|null $charFilter
     * @param TokenFilter[]|null $filter
     */
    public function __construct(
        private ?array $analyzer = null,
        private ?array $tokenizer = null,
        private ?array $charFilter = null,
        private ?array $filter = null,
        private ?array $settings = null,
    ) {
    }

    /**
     * @return array|null
     */
    public function getConfiguration(): ?array
    {
        return array_filter(array_merge([
            'analyzer' => $this->convertToAssociativeArray($this->analyzer),
            'tokenizer' => $this->convertToAssociativeArray($this->tokenizer),
            'char_filter' => $this->convertToAssociativeArray($this->charFilter),
            'filter' => $this->convertToAssociativeArray($this->filter),
        ], $this->settings ?? []));
    }

    /**
     * @param AnalysisInterface[] $analyses
     * @return array|null
     */
    private function convertToAssociativeArray(?array $analyses): ?array
    {
        if (!$analyses) {
            return null;
        }

        $result = [];
        foreach ($analyses as $analysis) {
            $config = $analysis->getConfiguration();
            $result[$analysis->getName()] = $config;
        }

        return $result;
    }
}

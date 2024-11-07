<?php

namespace App\Elasticsearch\Index\Analysis;

interface AnalysisInterface
{
    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string|null
     */
    public function getName(): ?string;

    /**
     * @return array|null
     */
    public function getConfiguration(): ?array;
}
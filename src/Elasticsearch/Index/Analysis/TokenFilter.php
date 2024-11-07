<?php

namespace App\Elasticsearch\Index\Analysis;

class TokenFilter implements AnalysisInterface
{
    public const TYPE_APOSTROPHE = 'apostrophe';
    public const TYPE_ASCIIFOLDING = 'asciifolding';
    public const TYPE_CJK_BIGRAM = 'cjk_bigram';
    public const TYPE_CJK_WIDTH = 'cjk_width';
    public const TYPE_CLASSIC = 'classic';
    public const TYPE_COMMON_GRAMS = 'common_grams';
    public const TYPE_CONDITIONAL = 'condition';
    public const TYPE_DECIMAL_DIGIT = 'decimal_digit';
    public const TYPE_DELIMITED_PAYLOAD = 'delimited_payload';
    public const TYPE_DICT_DECOMPOUNDER = 'dict_decompounder';
    public const TYPE_EDGE_NGRAM = 'edge_ngram';
    public const TYPE_ELISION = 'elision';
    public const TYPE_FINGERPRINT = 'fingerprint';
    public const TYPE_FLATTEN_GRAPH = 'flatten_graph';
    public const TYPE_HUNSPELL = 'hunspell';
    public const TYPE_HYP_DECOMPOUNDER = 'hyp_decompounder';
    public const TYPE_KEEP_TYPES = 'keep_types';
    public const TYPE_KEEP_WORDS = 'keep_words';
    public const TYPE_KEYWORD_MARKER = 'keyword_marker';
    public const TYPE_KEYWORD_REPEAT = 'keyword_repeat';
    public const TYPE_KSTEM = 'kstem';
    public const TYPE_LENGTH = 'length';
    public const TYPE_LIMIT_TOKEN_COUNT = 'limit_token_count';
    public const TYPE_LOWERCASE = 'lowercase';
    public const TYPE_MINHASH = 'minhash';
    public const TYPE_MULTIPLEXER = 'multiplexer';
    public const TYPE_NGRAM = 'ngram';
    public const TYPE_NORMALIZATION = 'normalization';
    public const TYPE_PATTERN_CAPTURE = 'pattern_capture';
    public const TYPE_PATTERN_REPLACE = 'pattern_replace';
    public const TYPE_PHONETIC = 'phonetic';
    public const TYPE_PORTERSTEM = 'porter_stem';
    public const TYPE_PREDICATE_SCRIPT = 'predicate_script';
    public const TYPE_REMOVE_DUPLICATES = 'remove_duplicates';
    public const TYPE_REVERSE = 'reverse';
    public const TYPE_SHINGLE = 'shingle';
    public const TYPE_SNOWBALL = 'snowball';
    public const TYPE_STEMMER = 'stemmer';
    public const TYPE_STEMMER_OVERRIDE = 'stemmer_override';
    public const TYPE_STOP = 'stop';
    public const TYPE_SYNONYM = 'synonym';
    public const TYPE_SYNONYM_GRAPH = 'synonym_graph';
    public const TYPE_TRIM = 'trim';
    public const TYPE_TRUNCATE = 'truncate';
    public const TYPE_UNIQUE = 'unique';
    public const TYPE_UPPERCASE = 'uppercase';
    public const TYPE_WORD_DELIMITER = 'word_delimiter';
    public const TYPE_WORD_DELIMITER_GRAPH = 'word_delimiter_graph';

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

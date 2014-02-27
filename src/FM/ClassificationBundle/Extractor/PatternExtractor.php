<?php

namespace FM\ClassificationBundle\Extractor;

class PatternExtractor implements ExtractorInterface
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @param string $pattern
     */
    public function __construct($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritdoc
     */
    public function extract($text)
    {
        $matches = [];
        $numberOfMatches = preg_match_all($this->pattern, $text, $matches);
        if ($numberOfMatches > 0 && array_key_exists(1, $matches)) {
            return $matches[1];
        }

        return null;
    }
}

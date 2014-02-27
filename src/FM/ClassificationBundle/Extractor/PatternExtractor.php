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
        $numberOfMatches = preg_match_all($this->pattern, $text, $matches);
        if ($numberOfMatches > 0) {
            if (array_key_exists(1, $matches)) {
                return $matches[1];
            } else {
                throw new \Exception("You must use wildcards in your pattern to have something extracted");
            }
        }

        return null;
    }
}

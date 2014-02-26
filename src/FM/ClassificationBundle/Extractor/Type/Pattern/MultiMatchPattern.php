<?php

namespace FM\ClassificationBundle\Extractor\Type\Pattern;

use FM\ClassificationBundle\Extractor\Type\PatternAbstract;

class MultiMatchPattern extends PatternAbstract
{
    /**
     * @inheritdoc
     */
    public function match($sourceText)
    {
        $pattern         = $this->getPattern();
        $numberOfMatches = preg_match_all($pattern, $sourceText, $matches);
        if ($numberOfMatches > 0) {
            return $this->determineReturnValue($matches[0], $sourceText);
        }
    }
}

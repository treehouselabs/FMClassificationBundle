<?php

namespace FM\ClassificationBundle\Extractor\Type\Pattern;

use FM\ClassificationBundle\Extractor\Type\PatternAbstract;

class SingleMatchPattern extends PatternAbstract
{
    /**
     * @inheritdoc
     */
    public function match($sourceText)
    {
        $pattern = $this->getPattern();
        $matches = array();
        $success = (bool) preg_match($pattern, $sourceText, $matches);
        if ($success === true) {
            return $this->determineReturnValue($matches[1], $sourceText);
        }
    }
}

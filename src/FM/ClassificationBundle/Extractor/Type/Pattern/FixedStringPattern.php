<?php

namespace FM\ClassificationBundle\Extractor\Type\Pattern;

use FM\ClassificationBundle\Extractor\Type\PatternAbstract;

class FixedStringPattern extends PatternAbstract
{
    /**
     * @inheritdoc
     */
    public function match($sourceText)
    {
        $pattern = $this->getPattern();
        if (strstr($sourceText, $pattern)) {
            return $this->determineReturnValue($pattern, $sourceText);
        }

        return null;
    }
}

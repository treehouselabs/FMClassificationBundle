<?php

namespace FM\ClassificationBundle\Extractor\Type;

use FM\ClassificationBundle\Extractor\ExtractorInterface;

abstract class PatternExtractorAbstract implements ExtractorInterface
{
    /**
     * @inheritdoc
     */
    public function extract($sourceText)
    {
        foreach ($this->getMatchingPatterns() as $patternObject) {
            if (!is_object($patternObject) || !($patternObject instanceof PatternInterface)) {
                throw new \InvalidArgumentException("Patterns returned by an extractor should be objects implementing FM\\ClassificationBundle\\Extractor\\Type\\PatternInterface");
            }

            $match = $patternObject->match($sourceText);
            if ($match !== null) {
                return $match;
            }
        }

        return null;
    }

    /**
     * @return PatternInterface[]
     */
    abstract public function getMatchingPatterns();
}

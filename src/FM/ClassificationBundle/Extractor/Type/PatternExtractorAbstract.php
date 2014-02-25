<?php

namespace FM\ClassificationBundle\Extractor\Type;

use FM\ClassificationBundle\Extractor\ExtractorInterface;
use FM\ClassificationBundle\Extractor\Type\Pattern\FixedStringPattern;
use FM\ClassificationBundle\Extractor\Type\Pattern\MultiMatchPattern;
use FM\ClassificationBundle\Extractor\Type\Pattern\SingleMatchPattern;

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

            if ($patternObject instanceof SingleMatchPattern) {
                $pattern = $patternObject->getPattern();
                $matches = array();
                $success = (bool) preg_match($pattern, $sourceText, $matches);
                if ($success === true) {
                    return $this->determineReturnValue($patternObject, $matches[1], $sourceText);
                }
            } elseif ($patternObject instanceof MultiMatchPattern) {
                $pattern = $patternObject->getPattern();
                $numberOfMatches = preg_match_all($pattern, $sourceText, $matches);
                if ($numberOfMatches > 0) {
                    return $this->determineReturnValue($patternObject, $matches[0], $sourceText);
                }
            } elseif ($patternObject instanceof FixedStringPattern) {
                $pattern = $patternObject->getPattern();
                if (strstr($sourceText, $pattern)) {
                    return $this->determineReturnValue($patternObject, $pattern, $sourceText);
                }
            } else {
                throw new \InvalidArgumentException(sprintf("Unknown pattern to extract with: must be instance of SingleMatchPattern, MultiMatchPattern or FixedStringPattern; %s given", get_class($patternObject)));
            }
        }

        return null;
    }

    /**
     * @param PatternInterface $patternObject
     * @param $initialValue
     * @return mixed
     */
    protected function determineReturnValue(PatternInterface $patternObject, $initialValue, $sourceText)
    {
        if (is_null($patternObject->getAssignedValue())) {
            return $initialValue;
        }

        if (is_callable($patternObject->getAssignedValue())) {
            return call_user_func_array($patternObject->getAssignedValue(), [$initialValue, $sourceText]);
        }

        return $initialValue;
    }

    /**
     * @return PatternInterface[]
     */
    abstract public function getMatchingPatterns();
}

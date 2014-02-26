<?php

namespace FM\ClassificationBundle\Extractor\Type;

abstract class PatternAbstract implements PatternInterface
{
    /**
     * @var string
     */
    protected $pattern;

    /**
     * @var mixed|null
     */
    protected $assignedValue;

    /**
     * @var bool
     */
    protected $caseSensitive;

    /**
     * @inheritdoc
     */
    public function __construct($pattern, $assignedValue = null)
    {
        $this->pattern = $pattern;
        $this->assignedValue = $assignedValue;
    }

    /**
     * @inheritdoc
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @inheritdoc
     */
    public function getAssignedValue()
    {
        return $this->assignedValue;
    }

    /**
     * @param $extractedValue
     * @param $sourceText
     * @return mixed
     */
    protected function determineReturnValue($extractedValue, $sourceText)
    {
        if (is_callable($this->getAssignedValue())) {
            // a callable is assigned to this match, execute it and return it's value
            return call_user_func_array($this->getAssignedValue(), [$extractedValue, $sourceText]);
        }

        // just return the extracted value
        return $extractedValue;
    }
}

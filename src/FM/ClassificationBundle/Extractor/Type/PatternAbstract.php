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
}

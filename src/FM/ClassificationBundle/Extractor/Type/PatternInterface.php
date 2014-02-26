<?php

namespace FM\ClassificationBundle\Extractor\Type;

interface PatternInterface
{
    /**
     * @param string     $pattern
     * @param mixed|null $assignedValue
     */
    public function __construct($pattern, $assignedValue = null);

    /**
     * @return string
     */
    public function getPattern();

    /**
     * @return mixed
     */
    public function getAssignedValue();

    /**
     * @param $value
     * @return mixed
     */
    public function match($value);
}

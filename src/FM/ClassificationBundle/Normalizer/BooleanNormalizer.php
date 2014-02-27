<?php

namespace FM\ClassificationBundle\Normalizer;

class BooleanNormalizer implements NormalizerInterface
{
    protected $trueValues;

    public function __construct(array $trueValues = ['y', 'yes'])
    {
        $this->trueValues = $trueValues;
    }

    /**
     * @param $value
     * @return bool|null
     */
    public function normalize($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_bool($value)) {
            return $value;
        }

        if (is_numeric($value) && intval($value) === 1) {
            return true;
        }

        if (is_string($value)) {
            foreach ($this->trueValues as $trueValue) {
                if (strcasecmp($value, $trueValue) == 0) {
                    return true;
                }
            }
        }

        return false;
    }
}

<?php

namespace FM\ClassificationBundle\Normalizer;

class BooleanNormalizer implements NormalizerInterface
{
    protected $nonEmptyStringsAsTrue = false;
    protected $trueValues = [];

    public function __construct($nonEmptyStringsAsTrue = false, array $trueValues = ['y', 'yes'])
    {
        $this->nonEmptyStringsAsTrue = $nonEmptyStringsAsTrue;
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
            if ($this->nonEmptyStringsAsTrue === true && $value != '') {
                return true;
            }
            foreach ($this->trueValues as $trueValue) {
                if (strcasecmp($value, $trueValue) == 0) {
                    return true;
                }
            }
        }

        return false;
    }
}

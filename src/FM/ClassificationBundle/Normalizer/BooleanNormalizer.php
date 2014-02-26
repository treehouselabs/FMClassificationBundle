<?php

namespace FM\ClassificationBundle\Normalizer;

class BooleanNormalizer implements NormalizerInterface
{
    /**
     * @param $value
     * @return bool|null
     */
    public function normalize($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_scalar($value)) {
            return (bool) $value;
        }

        return false;
    }
}

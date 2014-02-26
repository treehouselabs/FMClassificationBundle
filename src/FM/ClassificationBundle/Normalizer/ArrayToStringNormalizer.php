<?php

namespace FM\ClassificationBundle\Normalizer;

class ArrayToStringNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        if (is_null($value)) {
            return null;
        }

        if (is_scalar($value)) {
            return $value;
        }

        if (is_array($value)) {
            return reset($value);
        }

        return null;
    }
}

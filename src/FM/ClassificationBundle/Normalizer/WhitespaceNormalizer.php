<?php

namespace FM\ClassificationBundle\Normalizer;

class WhitespaceNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        return $value;
    }
}

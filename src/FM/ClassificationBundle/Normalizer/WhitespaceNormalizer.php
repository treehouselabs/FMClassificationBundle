<?php

namespace FM\ClassificationBundle\Normalizer;

class WhitespaceNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        return $value;
    }
}

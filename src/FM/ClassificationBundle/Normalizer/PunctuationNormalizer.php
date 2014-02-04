<?php

namespace FM\ClassificationBundle\Normalizer;

class PunctuationNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        return preg_replace('/[^a-z0-9\s\n]+/i', '', $value);
    }
}

<?php

namespace FM\ClassificationBundle\Normalizer;

class LowercaseNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        return strtolower($value);
    }
}

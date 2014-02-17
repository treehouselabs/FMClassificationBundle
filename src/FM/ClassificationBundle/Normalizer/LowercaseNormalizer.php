<?php

namespace FM\ClassificationBundle\Normalizer;

class LowercaseNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(sprintf('Expected string, got "%s": %s', gettype($value), var_export($value, true)));
        }

        return strtolower($value);
    }
}

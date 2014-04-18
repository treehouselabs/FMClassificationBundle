<?php

namespace FM\ClassificationBundle\Normalizer;

class WhitespaceNormalizer implements NormalizerInterface
{
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        // Normalizes the unicode character which in html-entities is known as '&nbsp;'
        $value = str_replace("\xC2\xA0", " ", $value);
        $value = str_replace("&nbsp;", " ", $value);

        $value = preg_replace('/\s+/', ' ', $value);
        $value = trim($value);

        return $value;
    }
}

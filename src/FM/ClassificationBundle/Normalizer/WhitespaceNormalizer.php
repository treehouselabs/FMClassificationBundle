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
        $value = str_replace("\r", '', $value);

        $value = preg_replace('/ +/', ' ', $value);
        $value = str_replace(" \n", "\n", $value);
        $value = trim($value);

        return $value;
    }
}

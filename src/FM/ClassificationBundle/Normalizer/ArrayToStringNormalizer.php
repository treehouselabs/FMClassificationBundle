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
            return $this->implodeRecursively($value);
        }

        return null;
    }

    protected function implodeRecursively(array $array, $glue = '')
    {
        $imploded = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $imploded .= $this->implodeRecursively($item, $glue) . $glue;
            } elseif (is_scalar($item)) {
                $imploded .= $item . $glue;
            } else {
                throw new \OutOfBoundsException(sprintf("Can't implode a non-array: %s", var_export($item, true)));
            }
        }

        return $imploded;
    }
}

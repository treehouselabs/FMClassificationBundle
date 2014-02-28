<?php

namespace FM\ClassificationBundle\Normalizer;

class ArrayToStringNormalizer implements NormalizerInterface
{
    /**
     * @var string
     */
    protected $glue;

    /**
     * @param string $glue
     */
    public function __construct($glue = '')
    {
        $this->glue = $glue;
    }

    /**
     * @param $value
     * @return null|string
     */
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

    /**
     * @param  array                 $array
     * @return string
     * @throws \OutOfBoundsException
     */
    protected function implodeRecursively(array $array)
    {
        $imploded = '';

        foreach ($array as $item) {
            if (is_array($item)) {
                $imploded .= $this->implodeRecursively($item) . $this->glue;
            } elseif (is_scalar($item)) {
                if ($imploded == '') {
                    $imploded .= $item . $this->glue;
                } else {
                    $imploded .= $item . $this->glue;
                }
            } else {
                throw new \OutOfBoundsException(sprintf("Can't implode a non-array: %s", var_export($item, true)));
            }
        }

        if ($this->glue != '') {
            $imploded = substr($imploded, 0, -(strlen($this->glue)));
        }

        return $imploded;
    }
}

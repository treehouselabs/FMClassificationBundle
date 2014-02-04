<?php

namespace FM\ClassificationBundle\Normalizer;

/**
 * Takes an array of normalizers and applies them all
 */
class ChainNormalizer implements NormalizerInterface
{
    /**
     * @var NormalizerInterface[]
     */
    protected $normalizers;

    /**
     * Constructor.
     *
     * @param array $normalizers
     */
    public function __construct(array $normalizers)
    {
        $this->normalizers = $normalizers;
    }

    public function normalize($value)
    {
        foreach ($this->normalizers as $normalizer) {
            $value = $normalizer->normalize($value);
        }

        return $value;
    }
}

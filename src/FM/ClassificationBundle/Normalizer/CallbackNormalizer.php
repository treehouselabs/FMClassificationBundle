<?php

namespace FM\ClassificationBundle\Normalizer;

class CallbackNormalizer implements NormalizerInterface
{
    protected $callback;

    public function __construct($callback)
    {
        $this->callback = $callback;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        return call_user_func_array($this->callback, array($value));
    }
}

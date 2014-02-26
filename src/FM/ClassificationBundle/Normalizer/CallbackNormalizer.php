<?php

namespace FM\ClassificationBundle\Normalizer;

class CallbackNormalizer implements NormalizerInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param callable $callable
     */
    public function __construct(\Closure $callable)
    {
        $this->callable = $callable;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        return call_user_func_array($this->callable, array($value));
    }
}

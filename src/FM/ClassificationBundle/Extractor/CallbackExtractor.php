<?php

namespace FM\ClassificationBundle\Extractor;

class CallbackExtractor implements ExtractorInterface
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
     * @inheritdoc
     */
    public function extract($text)
    {
        return call_user_func_array($this->callable, array($text, null, $this));
    }
}

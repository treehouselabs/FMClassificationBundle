<?php

namespace FM\ClassificationBundle\Extractor;

class CallbackExtractor implements ExtractorInterface
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param ExtractorInterface $extractor
     * @param callable           $callable
     */
    public function __construct(ExtractorInterface $extractor, \Closure $callable)
    {
        $this->extractor = $extractor;
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function extract($text)
    {
        $extracted = $this->extractor->extract($text);
        if ($extracted !== null) {
            return call_user_func_array($this->callable, array($text, $extracted, $this));
        }

        return $extracted;
    }
}

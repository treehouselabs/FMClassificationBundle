<?php

namespace FM\ClassificationBundle\Extractor;

class VotingExtractor implements ExtractorInterface
{
    /**
     * @var ExtractorInterface[]
     */
    protected $extractors;

    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param ExtractorInterface[] $extractors
     * @param callable|null        $callable
     */
    public function __construct(array $extractors, \Closure $callable)
    {
        $this->extractors = $extractors;
        $this->callable = $callable;
    }

    /**
     * @inheritdoc
     */
    public function extract($text)
    {
        foreach ($this->extractors as $extractor) {
            $extracted = $extractor->extract($text);
            if ($extracted !== null) {
                if (false !== $result = call_user_func_array($this->callable, array($text, &$extracted, $extractor))) {
                    var_dump($extracted);

                    return $extracted;
                }
            }
        }

        return null;
    }
}

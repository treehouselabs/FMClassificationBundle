<?php

namespace FM\ClassificationBundle\Extractor;

class FirstMatchExtractor implements ExtractorInterface
{
    /**
     * @var ExtractorInterface[]
     */
    protected $extractors;

    /**
     * @param ExtractorInterface[] $extractors
     */
    public function __construct(array $extractors)
    {
        $this->extractors = $extractors;
    }

    /**
     * @inheritdoc
     */
    public function extract($text)
    {
        foreach ($this->extractors as $extractor) {
            $extracted = $extractor->extract($text);
            if ($extracted !== null) {
                return $extracted;
            }
        }

        return null;
    }
}

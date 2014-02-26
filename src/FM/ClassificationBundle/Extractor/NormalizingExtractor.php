<?php

namespace FM\ClassificationBundle\Extractor;

use FM\ClassificationBundle\Normalizer\NormalizerInterface;

class NormalizingExtractor implements ExtractorInterface
{
    /**
     * @var ExtractorInterface
     */
    protected $extractor;

    /**
     * @var NormalizerInterface
     */
    protected $normalizer;

    /**
     * @param ExtractorInterface $extractor
     * @param NormalizerInterface $normalizer
     */
    public function __construct(ExtractorInterface $extractor, NormalizerInterface $normalizer)
    {
        $this->extractor = $extractor;
        $this->normalizer = $normalizer;
    }

    /**
     * @inheritdoc
     */
    public function extract($text)
    {
        $extracted = $this->extractor->extract($text);
        if ($extracted !== null) {
            $normalized = $this->normalizer->normalize($extracted);
            return $normalized;
        }

        return $extracted;
    }
}

<?php

namespace FM\ClassificationBundle\Extractor;

interface ExtractorInterface
{
    /**
     * Extracts a specific value from a given text
     *
     * @param $sourceText
     * @return mixed
     */
    public function extract($sourceText);
}

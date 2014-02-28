<?php

namespace FM\ClassificationBundle\Extractor;

interface ExtractorInterface
{
    /**
     * Extracts a specific value from a given text
     *
     * @param $text
     * @return mixed
     */
    public function extract($text);
}

<?php

namespace FM\ClassificationBundle\Extractor;

interface ExtractorInterface
{
    /**
     * @param string $data
     *
     * @return array<string>
     */
    public function extract($data);
}

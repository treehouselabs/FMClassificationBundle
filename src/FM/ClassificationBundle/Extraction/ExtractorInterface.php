<?php

namespace FM\ClassificationBundle\Extraction;

interface ExtractorInterface
{
    /**
     * @param string $data
     *
     * @return array<string>
     */
    public function extract($data);
}

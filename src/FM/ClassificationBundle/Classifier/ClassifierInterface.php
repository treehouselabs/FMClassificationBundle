<?php

namespace FM\ClassificationBundle\Classifier;

interface ClassifierInterface
{
    /**
     * @param  mixed $input
     * @param  float $confidence a score between 0 and 1 (1 = 100%)
     * @return mixed
     */
    public function classify($input, &$confidence = null);
}

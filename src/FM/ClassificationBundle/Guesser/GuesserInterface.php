<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;

interface GuesserInterface
{
    /**
     * @param  mixed              $value
     * @return WeightedCollection
     */
    public function guess($value);
}

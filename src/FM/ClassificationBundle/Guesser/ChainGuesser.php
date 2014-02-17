<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;

class ChainGuesser implements GuesserInterface
{
    /**
     * @var array[<GuesserInterface, integer>]
     */
    protected $guessers;

    /**
     * @param GuesserInterface $guesser
     * @param int              $boost
     * @return $this
     */
    public function addGuesser(GuesserInterface $guesser, $boost = 1)
    {
        $this->guessers[] = [$guesser, $boost];

        return $this;
    }

    /**
     * @param  mixed              $value
     * @return WeightedCollection
     */
    public function guess($value)
    {
        $retval = null;

        $max = 0;
        foreach ($this->guessers as list($guesser, $boost)) {
            $max += (1 * $boost);
        }

        $baseWeight = 1 / $max;

        $results = new WeightedCollection();

        /** @var GuesserInterface $guesser */
        foreach ($this->guessers as list($guesser, $boost)) {
            $guesses = $guesser->guess($value);

            $results->merge($guesses, $baseWeight * $boost);
        }

        return $results;
    }
}

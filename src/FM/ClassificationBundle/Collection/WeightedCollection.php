<?php

namespace FM\ClassificationBundle\Collection;

class WeightedCollection
{
    /**
     * @var array[<mixed, float>]
     */
    protected $collection = [];

    /**
     * @param  mixed                 $value
     * @param  integer               $score
     * @throws \OutOfBoundsException
     */
    public function add($value, $score)
    {
        if ($score < 0 || $score > 1) {
            throw new \OutOfBoundsException(sprintf(
                'Score can only be between 0 and 1, %f given',
                $score
            ));
        }

        $this->collection[] = [$value, $score];
    }

    /**
     * Returns score for value, returns 0 when value isn't found
     *
     * @param mixed $value
     * @return float
     */
    public function getScore($value)
    {
        foreach ($this->collection as $key => list($existingValue, $existingScore)) {
            if ($existingValue === $value) {
                return $existingScore;
            }
        }

        return 0;
    }

    /**
     * @return array
     */
    public function all()
    {
        $collection = clone $this;
        $collection->sort();

        $retval = array();

        foreach ($collection->raw() as list($value, $score)) {
            $retval[] = $value;
        }

        return $retval;
    }

    /**
     * @return int|null|string
     */
    public function top()
    {
        $collection = clone $this;
        $collection->sort();

        foreach ($collection->raw() as list($value, $score)) {
            return $value;
        }

        return null;
    }

    /**
     * @return int
     */
    public function topScore()
    {
        $collection = clone $this;
        $collection->sort();

        foreach ($collection->raw() as list($value, $score)) {
            return $score;
        }

        return 0;
    }

    /**
     * @return mixed
     */
    public function raw()
    {
        $this->sort();

        return $this->collection;
    }

    /**
     * @param  WeightedCollection $collection
     * @param  int                $weight
     * @throws \LogicException
     */
    public function merge(WeightedCollection $collection, $weight = 1)
    {
        $all = $this->all();

        $max = 0;
        foreach ($collection->raw() as list($value, $score)) {
            if ($score > $max) {
                $max = $score * $weight;
            }
        }

        foreach ($this->collection as list($value, $score)) {
            if ($score > $max) {
                $max = $score;
            }
        }

        $originalCollection = clone $this;

        foreach ($collection->raw() as list($value, $score)) {
            if (!in_array($value, $all, true)) {
                $this->add($value, ($score * $weight));
            } else {
                foreach ($this->collection as $key => list($existingValue, $existingScore)) {
                    if ($existingValue === $value) {
                        $newScore = $existingScore + ($score * $weight);

                        $this->collection[$key] = [
                            $existingValue,
                            $newScore
                        ];
                    }
                }
            }
        }

        $sumMax = 0;
        foreach ($this->collection as list($value, $score)) {
            if ($score > $sumMax) {
                $sumMax = $score;
            }
        }

        if ($sumMax > 0) {
            $remaining = 1 - $max;

            foreach ($this->collection as $key => list($existingValue, $existingScore)) {
                // skip score bump for newly merged items
                if (in_array($existingValue, $collection->all())) {
                    continue;
                }

                $a = $originalCollection->getScore($existingValue); // original score
                $b = $this->getScore($existingValue);               // new score after merge

                $certainty = $a + $b - ($a * $b);

                $newScore = $existingScore * $max / $sumMax;
                $newScore+= 0.5 * ($remaining * $certainty); // boost score a little depending on certainty

                $this->collection[$key] = [
                    $existingValue,
                    $newScore
                ];
            }
        }
    }

    /**
     * @param $mapperCallback callback that gets applied to every value in the collection
     */
    public function map($mapperCallback)
    {
        foreach ($this->collection as $key => list($existingValue, $existingScore)) {
            $this->collection[$key] = [$mapperCallback($existingValue), $existingScore];
        }
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->collection);
    }

    /**
     * Sorts the collection on score (descending)
     */
    protected function sort()
    {
        $values = array();
        foreach ($this->collection as list($value, $score)) {
            $values[] = $score;
        }
        $keys = array_keys($this->collection);

        array_multisort($values, SORT_DESC, SORT_NUMERIC, $keys);

        $newCollection = [];
        foreach ($keys as $key) {
            $newCollection[] = $this->collection[$key];
        }

        $this->collection = $newCollection;
    }
}

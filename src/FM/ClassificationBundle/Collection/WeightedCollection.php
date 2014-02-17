<?php

namespace FM\ClassificationBundle\Collection;

class WeightedCollection
{
    protected $collection = [];

    /**
     * @param mixed $value
     * @param integer $score
     * @throws \OutOfBoundsException
     */
    public function add($value, $score)
    {
        if ($score < 0 || $score > 1) {
            throw new \OutOfBoundsException(sprintf('Score can only be between 0 and 1, %f give', $score));
        }

        $this->collection[] = [$value, $score];
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
     * @param WeightedCollection $collection
     * @param int $weight
     * @throws \LogicException
     */
    public function merge(WeightedCollection $collection, $weight = 1)
    {
        $all = $this->all();

        foreach ($collection->raw() as list($value, $score)) {
            if (!in_array($value, $all, true)) {
                $this->add($value, ($score * $weight));
            } else {
                foreach ($this->collection as $key => list($existingValue, $existingScore)) {
                    if ($existingValue === $value) {
                        $this->collection[$key] = [$existingValue, $existingScore + ($score * $weight)];
                    }
                }
            }
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
        foreach ($this->collection as $key => list($value, $score)) {
            $values[] = $score;
        }
        $keys = array_keys($this->collection);

        array_multisort($values, SORT_DESC, SORT_NUMERIC, $keys);

        $newCollection = [];
        foreach($keys as $key) {
            $newCollection[] = $this->collection[$key];
        }

        $this->collection = $newCollection;
    }
}

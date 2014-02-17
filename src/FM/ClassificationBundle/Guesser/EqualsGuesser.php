<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;
use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Normalizer\NormalizerInterface;

class EqualsGuesser implements GuesserInterface
{
    protected $tokenizer;

    protected $normalizer;

    protected $dataSource;

    public function __construct(
        NormalizerInterface $normalizer,
        DataSourceInterface $dataSource
    )
    {
        $this->normalizer = $normalizer;
        $this->dataSource = $dataSource;
    }

    /**
     * @param  mixed            $value
     * @return WeightedCollection
     */
    public function guess($value)
    {
        $retval = new WeightedCollection();

        $value = $this->normalizer->normalize($value);

        if (null === $value) {
            return $retval;
        }

        foreach ($this->dataSource as $item) {
            $string = $this->normalizer->normalize($item);

            if ($string === $value) {
                $retval->add($item, 1);
            }
        }

        return $retval;
    }
}

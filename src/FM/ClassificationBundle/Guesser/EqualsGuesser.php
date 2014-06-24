<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;
use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Normalizer\NormalizerInterface;

class EqualsGuesser implements GuesserInterface
{
    /**
     * @var \FM\ClassificationBundle\Normalizer\NormalizerInterface
     */
    protected $dataSourceNormalizer;

    /**
     * @var \FM\ClassificationBundle\Normalizer\NormalizerInterface
     */
    protected $normalizer;

    /**
     * @var \FM\ClassificationBundle\DataSource\DataSourceInterface
     */
    protected $dataSource;

    /**
     * Constructor.
     *
     * @param NormalizerInterface $normalizer
     * @param DataSourceInterface $dataSource
     * @param \FM\ClassificationBundle\Normalizer\NormalizerInterface $dataSourceNormalizer
     */
    public function __construct(
        DataSourceInterface $dataSource,
        NormalizerInterface $normalizer = null,
        NormalizerInterface $dataSourceNormalizer = null
    ) {
        $this->normalizer = $normalizer;
        $this->dataSourceNormalizer = $dataSourceNormalizer;
        $this->dataSource = $dataSource;
    }

    /**
     * @param  mixed              $value
     * @return WeightedCollection
     */
    public function guess($value)
    {
        $retval = new WeightedCollection();

        if (null !== $this->normalizer) {
            $value = $this->normalizer->normalize($value);
        }

        if (null === $value) {
            return $retval;
        }

        foreach ($this->dataSource as $item) {
            $normalizedItem = $item;

            if ($this->dataSourceNormalizer) {
                $normalizedItem = $this->dataSourceNormalizer->normalize($item);
            }

            if (null !== $this->normalizer) {
                $normalizedItem = $this->normalizer->normalize($normalizedItem);
            }

            if ($normalizedItem === $value) {
                $retval->add($item, 1);
            }
        }

        return $retval;
    }
}

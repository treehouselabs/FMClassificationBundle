<?php

namespace FM\ClassificationBundle\Normalizer;


class StopWordsNormalizer implements NormalizerInterface
{
    /**
     * @var string[]
     */
    protected $stopWords;

    /**
     * Constructor.
     *
     * @param string[] $stopWords
     */
    public function __construct(array $stopWords)
    {
        $this->stopWords = $stopWords;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($value)
    {
        if (null === $value) {
            return null;
        }

        foreach ($this->stopWords as $word) {
            $value = str_ireplace($word, '', $value);
        }

        return $value;
    }
}

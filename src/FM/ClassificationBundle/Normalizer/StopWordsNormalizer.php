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
            $value = preg_replace('/([\s\n\t]|^)'.preg_quote($word, '/').'([\s\n\t]|$)/i', '\\1\\2', $value);
        }

        return $value;
    }
}

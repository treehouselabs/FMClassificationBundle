<?php

namespace FM\ClassificationBundle\Normalizer;

class StopWordsNormalizer implements NormalizerInterface
{
    protected $stopWords;
    protected $punctuation;

    /**
     * Constructor.
     *
     * @param array $stopWords
     * @param string $punctuation
     */
    public function __construct(array $stopWords, $punctuation = '$^\s\t\n\.,\?!_-')
    {
        $this->stopWords = $stopWords;
        $this->punctuation = $punctuation;
    }

    public function normalize($value)
    {
        foreach ($this->stopWords as $word) {
            //preg_replace('(?:' . $this->punctuation . ')(' . $word . ')(?:' . $this->punctuation . ')', '', $value);


            $value = str_replace($word, '', $value);
        }

        return $value;
    }
}

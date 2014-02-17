<?php

namespace FM\ClassificationBundle\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;
use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Normalizer\NormalizerInterface;
use FM\ClassificationBundle\Tokenizer\TokenizerInterface;

class StringGuesser implements GuesserInterface
{
    /**
     * @var \FM\ClassificationBundle\Tokenizer\TokenizerInterface
     */
    protected $tokenizer;

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
     * @param TokenizerInterface  $tokenizer
     * @param DataSourceInterface $dataSource
     */
    public function __construct(
        NormalizerInterface $normalizer,
        TokenizerInterface $tokenizer,
        DataSourceInterface $dataSource
    ) {
        $this->tokenizer  = $tokenizer;
        $this->normalizer = $normalizer;
        $this->dataSource = $dataSource;
    }

    /**
     * @param  mixed            $value
     * @return WeightedCollection
     */
    public function guess($value)
    {
        $value  = $this->normalizer->normalize($value);
        $tokens = $this->tokenizer->tokenize($value);

        $retval = new WeightedCollection();

        foreach ($this->dataSource as $item) {
            $itemValue  = $this->normalizer->normalize($item);
            $itemTokens = $this->tokenizer->tokenize($itemValue);

            if (implode(' ', $itemTokens) == implode(' ', $tokens)) {
                // exact match!
                $score = 1;
            } else {
                $score = 0;
                $maxScore = max(count($tokens), count($itemTokens));

//                $p = 0;
//                $l = levenshtein($value, $itemValue);
//                similar_text($value, $itemValue, $p);
//                var_dump($value, $itemValue, $l, $p);
                foreach ($tokens as $token) {
                    if (strstr($itemValue, $token)) {
                        $score++;
                    }
                }

                $score  = $score / $maxScore;
            }

            if ($score > 0) {
                $retval->add($item, $score);
            }
        }

        return $retval;
    }
}

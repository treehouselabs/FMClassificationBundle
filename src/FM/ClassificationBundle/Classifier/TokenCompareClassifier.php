<?php

namespace FM\ClassificationBundle\Classifier;

use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Normalizer\NormalizerInterface;
use Fieg\Bayes\TokenizerInterface;

class TokenCompareClassifier implements ClassifierInterface
{
    const EXACT_MATCH = 'exact';

    /**
     * @var \FM\ClassificationBundle\DataSource\DataSourceInterface
     */
    protected $dataSource;

    /**
     * @var \Fieg\Bayes\TokenizerInterface
     */
    protected $tokenizer;

    /**
     * @var \FM\ClassificationBundle\Normalizer\NormalizerInterface
     */
    protected $normalizer;

    /**
     * Constructor.
     *
     * @param DataSourceInterface $dataSource
     * @param TokenizerInterface  $tokenizer
     * @param NormalizerInterface $normalizer
     */
    public function __construct(DataSourceInterface $dataSource, TokenizerInterface $tokenizer,
        NormalizerInterface $normalizer)
    {
        $this->dataSource = $dataSource;
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
    }

    /**
     * {@inheritdoc}
     */
    public function classify($input, &$score = null)
    {
        $input = $this->normalizer->normalize($input);
        $tokens = $this->tokenizer->tokenize($input);

        $scoredList = array();
        $maxScore = count($tokens);

        foreach ($this->dataSource as $string) {
            $normalizedString = $this->normalizer->normalize($string);
            $_tokens = $this->tokenizer->tokenize($normalizedString);

            $score = 0;

            if (implode('', $tokens) === implode('', $_tokens)) {
                $score = self::EXACT_MATCH;
            } else {
                $score = 0;
                foreach ($tokens as $token) {
                    if (in_array($token, $_tokens)) {
                        $score++;
                    }
                }
            }

            $scoredList[] = array(
                'score' => $score,
                'string' => $string,
            );
        }

        $this->normalizeScores($scoredList, $maxScore);
        $this->sort($scoredList, 'score', SORT_DESC);

        $topResult = array_shift($scoredList);

        $output = $topResult['string'];
        $score = $topResult['score'];

        // only if we have a certain certainty
        if ($score >= 0.90) {
            return $output;
        }

        return null;
    }

    /**
     * Normalizes scores to floats between 0 and 1
     *
     * @param $scoredList
     * @param $maxScore
     */
    protected function normalizeScores(&$scoredList, $maxScore)
    {
        foreach ($scoredList as &$row) {
            if (self::EXACT_MATCH === $row['score']) {
                $row['score'] = 1;
            } else {
                $row['score'] = ($row['score'] / $maxScore) - 0.1; // partial matches always get less
            }
        }
    }

    /**
     * @param array      $array
     * @param string|int $column
     * @param int        $order  SORT_ASC or SORT_DESC
     */
    protected function sort(array &$array, $column, $order = SORT_ASC)
    {
        $values = array();
        foreach ($array as $key => $row) {
            $values[$key] = $row[$column];
        }

        array_multisort($values, $order, $array);
    }
}

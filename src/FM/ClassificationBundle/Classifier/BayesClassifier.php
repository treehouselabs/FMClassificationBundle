<?php

namespace FM\ClassificationBundle\Classifier;

use Fieg\Bayes\Classifier as BaseBayesClassifier;
use FM\ClassificationBundle\DataSource\DataSourceInterface;

/**
 * Small adapter for Fiegs' Bayes Classifier
 */
class BayesClassifier implements TrainableClassifierInterface
{
    protected $bayesClassifier;

    public function __construct(BaseBayesClassifier $bayesClassifier)
    {
        $this->bayesClassifier = $bayesClassifier;
    }

    public function classify($text, &$confidence = null)
    {
        $confidence = 0;
        $result = null;

        $result = $this->bayesClassifier->classify($text);

        if (count($result) > 0) {
            $keys = array_keys($result);

            $key = reset($keys);
            $confidence = $result[$key];

            return $key;
        }

        return null;
    }

    public function train(DataSourceInterface $dataSource)
    {
        foreach ($dataSource as list($title, $label)) {
            $this->bayesClassifier->train($label, $title);
        }
    }
}

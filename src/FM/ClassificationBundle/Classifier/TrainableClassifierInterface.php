<?php

namespace FM\ClassificationBundle\Classifier;

use FM\ClassificationBundle\DataSource\DataSourceInterface;

interface TrainableClassifierInterface extends ClassifierInterface
{
    public function train(DataSourceInterface $dataSource);
}

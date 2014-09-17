<?php

namespace FM\ClassificationBundle\Extraction\Training;

use FM\ClassificationBundle\Extraction\Training\Source\AbstractTrainingSource;

interface TrainableExtractorInterface
{
    /**
     * @return boolean
     */
    public function train();

    /**
     * @return boolean
     */
    public function isTrained();

    /**
     * @param AbstractTrainingSource $dataSource
     */
    public function setTrainingSource(AbstractTrainingSource $dataSource);

    /**
     * @return AbstractTrainingSource
     */
    public function getTrainingSource();
}

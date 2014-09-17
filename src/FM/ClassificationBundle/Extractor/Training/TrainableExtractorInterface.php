<?php

namespace FM\ClassificationBundle\Extractor\Training;

use FM\ClassificationBundle\Extractor\Training\Source\AbstractTrainingSource;

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

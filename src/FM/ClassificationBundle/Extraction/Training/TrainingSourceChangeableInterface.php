<?php

namespace FM\ClassificationBundle\Extraction\Training;

use FM\ClassificationBundle\Extraction\Training\Source\AbstractTrainingSource;

interface TrainingSourceChangeableInterface
{
    /**
     * @param AbstractTrainingSource $dataSource
     */
    public function setTrainingSource(AbstractTrainingSource $dataSource);

    /**
     * @return AbstractTrainingSource
     */
    public function getTrainingSource();
}

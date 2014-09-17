<?php

namespace FM\ClassificationBundle\Extraction\Training;

interface TrainableInterface
{
    /**
     * @return boolean
     */
    public function train();

    /**
     * @param string $data
     */
    public function trainOne($data);

    /**
     * @return boolean
     */
    public function isTrained();
}

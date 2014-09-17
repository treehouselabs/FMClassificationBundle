<?php

namespace FM\ClassificationBundle\Extraction;

use FM\ClassificationBundle\Extraction\Training\Source\AbstractTrainingSource;
use FM\ClassificationBundle\Extraction\Training\TrainingSourceChangeableInterface;

class KeywordExtractorProxy implements ExtractorInterface, TrainingSourceChangeableInterface
{
    /**
     * @var KeywordExtractor
     */
    protected $extractor;

    /**
     * @param KeywordExtractor $extractor
     */
    public function __construct(KeywordExtractor $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @inheritdoc
     */
    public function extract($input)
    {
        if (!$this->extractor->isTrained()) {
            // TODO storage!
            $this->extractor->train();
        }

        return $this->extractor->extract($input);
    }

    /**
     * @inheritdoc
     */
    public function setTrainingSource(AbstractTrainingSource $dataSource)
    {
        return $this->extractor->setTrainingSource($dataSource);
    }

    /**
     * @inheritdoc
     */
    public function getTrainingSource()
    {
        return $this->extractor->getTrainingSource();
    }


}

<?php

namespace FM\ClassificationBundle\Extractor;

use FM\ClassificationBundle\Extractor\Training\Source\AbstractTrainingSource;
use FM\ClassificationBundle\Extractor\Training\TrainableExtractorInterface;

/**
 * The keyword extractor proxy provides a caching and initialization layer above the concrete keyword extractor.
 * See proxy pattern: @link http://en.wikipedia.org/wiki/Proxy_pattern
 */
class KeywordExtractorProxy implements TrainableExtractorInterface
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

    /**
     * @inheritdoc
     */
    public function train()
    {
        return $this->extractor->train();
    }

    /**
     * @inheritdoc
     */
    public function isTrained()
    {
        return $this->extractor->isTrained();
    }


}

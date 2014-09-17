<?php

namespace FM\ClassificationBundle\Extraction;

use FM\ClassificationBundle\Extraction\Storage\StorageInterface;
use FM\ClassificationBundle\Extraction\Storage\StoreableInterface;
use FM\ClassificationBundle\Extraction\Tokenization\TokenizingInterface;
use FM\ClassificationBundle\Extraction\Training\Source\AbstractTrainingSource;
use FM\ClassificationBundle\Extraction\Training\TrainableInterface;
use FM\ClassificationBundle\Extraction\Training\TrainingSourceChangeableInterface;
use FM\ClassificationBundle\Tokenizer\TokenizerInterface;

class KeywordExtractor implements TrainableInterface, TokenizingInterface, StoreableInterface, TrainingSourceChangeableInterface
{
    const STORAGE_ID = 'keyword';

    /**
     * @var int
     */
    protected $docCount = 0;

    /**
     * @var array
     */
    protected $tokenDocCount = 0;

    /**
     * @var array
     */
    protected $tokens;

    /**
     * @var array
     */
    protected $maxTokenFrequency;

    /**
     * @var AbstractTrainingSource
     */
    protected $trainingSource;

    /**
     * @var StorageInterface
     */
    protected $storage;

    /**
     * @var TokenizerInterface
     */
    protected $tokenizer;

    /**
     * @inheritdoc
     */
    public function train()
    {
        if (!$this->trainingSource) {
            throw new \RuntimeException('Unable to train, no datasource found!');
        }

        $this->docCount = 0;
        $this->tokenDocCount = [];
        $this->tokens = [];
        $this->maxTokenFrequency = [];

        foreach ($this->trainingSource as $row) {
            $data = current($row);

            $this->trainOne($data);
        }

        return $this->docCount > 0;
    }

    /**
     * @param string $data
     */
    public function trainOne($data)
    {
        $tokens = $this->tokenizer->tokenize($data);

        $tokenFrequency = [];
        array_map(function($token) use (&$tokenFrequency) {
            // update tokens
            if (!isset($this->tokens[$token])) {
                $this->tokens[$token] = 0;
            }
            $this->tokens[$token]++;

            // update token doc count
            if (!isset($tokenFrequency[$token])) {
                if (!isset($this->tokenDocCount[$token])) {
                    $this->tokenDocCount[$token] = 0;
                }
                $this->tokenDocCount[$token]++;
                $tokenFrequency[$token] = 0;
            }

            $tokenFrequency[$token]++;
        }, $tokens);

        foreach ($tokenFrequency as $token => $frequency) {
            // update max token frequency
            if (isset($this->maxTokenFrequency[$token]) && $frequency > $this->maxTokenFrequency[$token]) {
                $this->maxTokenFrequency[$token] = $frequency;
            }
        }

        // update doc count
        $this->docCount++;
    }

    /**
     * @inheritdoc
     */
    public function isTrained()
    {
        return $this->docCount > 0;
    }

    /**
     * @inheritdoc
     */
    public function extract($input)
    {
        if ($this->docCount < 1) {
            throw new \RuntimeException('Unable to extract keywords, extractor has no data!');
        }

        $tokens = $this->tokenizer->tokenize($input);
        $tokenCount = count($tokens);

        // calculate token frequency within the document
        $tokenFrequency = [];
        array_map(function($token) use (&$tokenFrequency) {
            if (!isset($tokenFrequency[$token])) {
                $tokenFrequency[$token] = 0;
            }
            $tokenFrequency[$token]++;
        }, $tokens);

        // calculate tfidf foreach token
        $classifiedTokens = [];
        array_walk($tokenFrequency, function($frequency, $token) use ($tokenCount, &$classifiedTokens){
            // tfidf
            $tokenDocCount = 0;
            if (isset($this->tokenDocCount[$token])) {
                $tokenDocCount = $this->tokenDocCount[$token];
            }

            $maxSeenFrequency = 0;
            if (isset($this->maxTokenFrequency[$token])) {
                $maxSeenFrequency = $this->maxTokenFrequency[$token];
            }

//            $tfidf = ($frequency/(1 + $tokenCount)) * log($this->docCount/(1 + $tokenDocCount));
            $tfidf = 0.5 + ((0.5 * $frequency)/(1 + $maxSeenFrequency)) * log($this->docCount/(1 + $tokenDocCount));

            if (ctype_upper($token)) {
                $tfidf += 2;
            } elseif (ctype_upper($token[0])) {
                $tfidf += 1;
            }

            $classifiedTokens[$token] = $tfidf;
        });

        return $classifiedTokens;
    }

    /**
     * @inheritdoc
     */
    public function setTrainingSource(AbstractTrainingSource $dataSource)
    {
        $this->trainingSource = $dataSource;
    }

    /**
     * @inheritdoc
     */
    public function getTrainingSource()
    {
        return $this->trainingSource;
    }

    /**
     * @inheritdoc
     */
    public function setStorage(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @inheritdoc
     */
    public function getStorage()
    {
        return $this->storage;
    }

    /**
     * @inheritdoc
     */
    public function store()
    {
        $id = $this->storage->store([$this->docCount, $this->tokenDocCount, $this->tokens], self::STORAGE_ID);
        if (!$id) {
            throw new \RuntimeException(sprintf('Unable to store keyword extractor!'));
        }

        return $id;
    }

    /**
     * @inheritdoc
     */
    public function load()
    {
        $data = $this->storage->load(self::STORAGE_ID);
        if (!$data) {
            throw new \RuntimeException(sprintf('Unable to load keyword extractor!'));
        }

        list($this->docCount, $this->tokenDocCount, $this->tokens) = $data;

        return true;
    }

    /**
     * @inheritdoc
     */
    public function setTokenizer(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @inheritdoc
     */
    public function getTokenizer()
    {
        return $this->tokenizer;
    }
}

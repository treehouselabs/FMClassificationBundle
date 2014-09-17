<?php

namespace FM\ClassificationBundle\Extractor;

use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Tokenizer\TokenizerInterface;

class TFIDFExtractor implements ExtractorInterface
{
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
     * @var TokenizerInterface
     */
    protected $tokenizer;

    /**
     * @param TokenizerInterface $tokenizer
     */
    public function __construct(TokenizerInterface $tokenizer)
    {
        $this->tokenizer = $tokenizer;
    }

    /**
     * @param DataSourceInterface $dataSource DataSourceInterface $dataSource
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function train(DataSourceInterface $dataSource)
    {
        $this->docCount = 0;
        $this->tokenDocCount = [];
        $this->tokens = [];
        $this->maxTokenFrequency = [];

        foreach ($dataSource as $data) {
            if (!is_string($data)) {
                throw new \RuntimeException('Data source should only contain strings');
            }

            $this->trainOne($data);
        }

        return $this->docCount > 0;
    }

    /**
     * @param string $data
     */
    protected function trainOne($data)
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
    public function extract($input)
    {
        if ($this->docCount < 1) {
            throw new \RuntimeException('Unable to extract, extractor has no data!');
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
}

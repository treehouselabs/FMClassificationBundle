<?php

namespace FM\ClassificationBundle\Extraction\Tokenization;

use FM\ClassificationBundle\Tokenizer\TokenizerInterface;

interface TokenizingInterface
{
    /**
     * @param TokenizerInterface $tokenizer
     */
    public function setTokenizer(TokenizerInterface $tokenizer);

    /**
     * @return TokenizerInterface
     */
    public function getTokenizer();
} 

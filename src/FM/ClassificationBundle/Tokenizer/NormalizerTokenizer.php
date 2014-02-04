<?php

namespace FM\ClassificationBundle\Tokenizer;

use FM\ClassificationBundle\Normalizer\NormalizerInterface;

/**
 * Applies a Normalizer onto a Tokenizer
 */
class NormalizerTokenizer implements TokenizerInterface
{
    protected $tokenizer;
    protected $normalizer;

    /**
     * Constructor.
     */
    public function __construct(TokenizerInterface $tokenizer, NormalizerInterface $normalizer)
    {
        $this->tokenizer = $tokenizer;
        $this->normalizer = $normalizer;
    }

    public function tokenize($string)
    {
        $tokens = $this->tokenizer->tokenize($string);

        foreach ($tokens as &$token) {
            $token = $this->normalizer->normalize($token);
        }

        $tokens = array_filter($tokens);

        return $tokens;
    }
}

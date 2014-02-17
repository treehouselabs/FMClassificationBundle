<?php

namespace FM\ClassificationBundle\Tests\Tokenizer;

use FM\ClassificationBundle\Tokenizer\NormalizingTokenizer;

class NormalizingTokenizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param $input
     * @param $tokens
     * @param $normalizedTokens
     *
     * @dataProvider tokenizeDataProvider
     */
    public function testTokenize($input, $tokens, $normalizedTokens)
    {
        $tokenizerMock = $this->getMockBuilder('FM\ClassificationBundle\Tokenizer\TokenizerInterface')
            ->getMock();

        $tokenizerMock
            ->expects($this->once())            // test `tokenize` get called once
            ->method('tokenize')
            ->will($this->returnValue($tokens)) // simulate tokenization
        ;

        $normalizerMock = $this->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
            ->getMock();

        $index = 0;
        foreach ($tokens as $key => $token) {
            $normalizerMock
                ->expects($this->at($index++))  // test foreach token `normalize` gets called
                ->method('normalize')
                ->with($token)
                ->will($this->returnValue($normalizedTokens[$key])) // simulate normalization
            ;
        }

        $tokenizer = new NormalizingTokenizer($tokenizerMock, $normalizerMock);
        $result = $tokenizer->tokenize($input);

        $this->assertEquals($normalizedTokens, $result);
    }

    public function tokenizeDataProvider()
    {
        return [
            ['TOKEN1 TOKEN2 TOKEN3', ['TOKEN1  ', 'TOKEN2', '  TOKEN3'], ['token1', 'token2', 'token3']]
        ];
    }
}

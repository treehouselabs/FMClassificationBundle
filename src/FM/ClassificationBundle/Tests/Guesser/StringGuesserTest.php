<?php

namespace FM\ClassificationBundle\Tests\Guesser;

use FM\ClassificationBundle\DataSource\ArrayDataSource;
use FM\ClassificationBundle\Guesser\StringGuesser;
use FM\ClassificationBundle\Normalizer\LowercaseNormalizer;
use FM\ClassificationBundle\Tokenizer\WhitespaceAndPunctuationTokenizer;

class StringGuesserTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $normalizerMock = $this->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
            ->getMockForAbstractClass();

        $tokenizerMock = $this->getMockBuilder('FM\ClassificationBundle\Tokenizer\TokenizerInterface')
            ->getMockForAbstractClass();

        $dataSourceMock = $this->getMockBuilder('FM\ClassificationBundle\DataSource\DataSourceInterface')
            ->getMockForAbstractClass();

        $guesser = new StringGuesser($normalizerMock, $tokenizerMock, $dataSourceMock);

        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\StringGuesser', $guesser);
        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\GuesserInterface', $guesser);
    }

    /**
     * @dataProvider guessDataProvider
     */
    public function testGuess($dataSource, $guessValue, $top, $topScore)
    {
        $normalizer = new LowercaseNormalizer();
        $tokenizer  = new WhitespaceAndPunctuationTokenizer();
        $guesser    = new StringGuesser($normalizer, $tokenizer, $dataSource);

        $result = $guesser->guess($guessValue);

        $this->assertInstanceOf('FM\ClassificationBundle\Collection\WeightedCollection', $result);

        $this->assertEquals($top, $result->top());
        $this->assertEquals($topScore, $result->topScore());
    }

    public function guessDataProvider()
    {
        $simpleDataSource = new ArrayDataSource([
            'hello world',
            'another sentence',
            'last but least',
            'short',
        ]);

        return [
            [$simpleDataSource, 'hello world', 'hello world', 1],
            [$simpleDataSource, 'another', 'another sentence', 0.5],
            [$simpleDataSource, 'longer with short', 'short', 1/3],
            [$simpleDataSource, 'least', 'last but least', 1/3],
        ];
    }
}

<?php

namespace FM\ClassificationBundle\Tests\Guesser;

use FM\ClassificationBundle\DataSource\ArrayDataSource;
use FM\ClassificationBundle\Guesser\EqualsGuesser;
use FM\ClassificationBundle\Normalizer\LowercaseNormalizer;

class EqualsGuesserTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $normalizerMock = $this->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
            ->getMockForAbstractClass();

        $dataSourceMock = $this->getMockBuilder('FM\ClassificationBundle\DataSource\DataSourceInterface')
            ->getMockForAbstractClass();

        $guesser = new EqualsGuesser($normalizerMock, $dataSourceMock);

        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\EqualsGuesser', $guesser);
        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\GuesserInterface', $guesser);
    }

    /**
     * @dataProvider guessDataProvider
     */
    public function testGuess($dataSource, $guessValue, $top, $topScore)
    {
        $normalizer = new LowercaseNormalizer();
        $guesser    = new EqualsGuesser($normalizer, $dataSource);

        $result = $guesser->guess($guessValue);

        $this->assertInstanceOf('FM\ClassificationBundle\Collection\WeightedCollection', $result);

        $this->assertEquals($top, $result->top());
        $this->assertEquals($topScore, $result->topScore());
    }

    public function guessDataProvider()
    {
        $simpleDataSource = new ArrayDataSource(['first', 'second', 'third']);

        return [
            [$simpleDataSource, 'first', 'first', 1],
            [$simpleDataSource, 'second', 'second', 1],
            [$simpleDataSource, 'third', 'third', 1],
            [$simpleDataSource, 'fourth', null, 0],
        ];
    }
}
<?php

namespace FM\ClassificationBundle\Tests\Guesser;

use FM\ClassificationBundle\Collection\WeightedCollection;
use FM\ClassificationBundle\Guesser\ChainGuesser;

class ChainGuesserTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $guesser = new ChainGuesser();

        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\ChainGuesser', $guesser);
        $this->assertInstanceOf('FM\ClassificationBundle\Guesser\GuesserInterface', $guesser);
    }

    /**
     * @dataProvider chainGuesserModesDataProvider
     */
    public function testGuessCallsGuessOnEachAddedGuesser(ChainGuesser $guesser)
    {
        $guesserMock1 = $this->getMockBuilder('FM\ClassificationBundle\Guesser\GuesserInterface')
            ->getMockForAbstractClass();

        $guesserMock1
            ->expects($this->once())
            ->method('guess')
            ->with('foobar')
            ->will($this->returnValue(new WeightedCollection()))
        ;

        $guesserMock2 = $this->getMockBuilder('FM\ClassificationBundle\Guesser\GuesserInterface')
            ->getMockForAbstractClass();

        $guesserMock2
            ->expects($this->once())
            ->method('guess')
            ->with('foobar')
            ->will($this->returnValue(new WeightedCollection()))
        ;

        $guesser->addGuesser($guesserMock1);
        $guesser->addGuesser($guesserMock2);

        $guesser->guess('foobar');
    }

    /**
     * @dataProvider chainGuesserModesDataProvider
     */
    public function testCallsNormalizers(ChainGuesser $guesser)
    {
        $collection = new WeightedCollection();
        $collection->add('foobar', 1);

        $guesserMock1 = $this->getMockBuilder('FM\ClassificationBundle\Guesser\GuesserInterface')
            ->getMockForAbstractClass();

        $guesserMock1
            ->expects($this->once())
            ->method('guess')
            ->with('foobar')
            ->will($this->returnValue($collection))
        ;

        $inputNormalizerMock1 = $this->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
            ->getMockForAbstractClass();

        $inputNormalizerMock1
            ->expects($this->once())
            ->method('normalize')
            ->with('foobar')
            ->will($this->returnValue('foobar'))
        ;

        $outputNormalizerMock1 = $this->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
            ->getMockForAbstractClass();

        $outputNormalizerMock1
            ->expects($this->once())
            ->method('normalize')
            ->with('foobar')
            ->will($this->returnValue('foobar'))
        ;

        $guesser->addGuesser($guesserMock1, 1, $inputNormalizerMock1, $outputNormalizerMock1);

        $guesser->guess('foobar');
    }

    public function chainGuesserModesDataProvider()
    {
        return [
            [new ChainGuesser(ChainGuesser::MODE_OR)],
            [new ChainGuesser(ChainGuesser::MODE_CUMULATIVE)],
        ];
    }
}

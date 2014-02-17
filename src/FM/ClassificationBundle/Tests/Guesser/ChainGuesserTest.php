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

    public function testGuessCallsGuessOnEachAddedGuesser()
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

        $guesser = new ChainGuesser();
        $guesser->addGuesser($guesserMock1);
        $guesser->addGuesser($guesserMock2);

        $guesser->guess('foobar');
    }
}

<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\VotingExtractor;

class VotingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expectedInput = 'foobar';
        $expectedExtracted = 'bar';
        $expectedVote = true;

        $extractorMock = $this->getMockForAbstractClass('FM\ClassificationBundle\Extractor\ExtractorInterface');

        $extractorMock->expects($this->once())
            ->method('extract')
            ->with($expectedInput)
            ->will($this->returnValue($expectedExtracted));

        $testCase = $this;

        $isCalled = 0;

        $voter = function ($input, $extracted, $extractor) use ($testCase, $expectedInput, $extractorMock, &$isCalled, $expectedExtracted, $expectedVote) {
            $isCalled++;

            $testCase->assertEquals($expectedInput, $input);
            $testCase->assertEquals($expectedExtracted, $extracted);
            $testCase->assertSame($extractorMock, $extractor);

            return $expectedVote;
        };

        $extractor = new VotingExtractor([$extractorMock], $voter);
        $actual = $extractor->extract($expectedInput);

        $this->assertEquals(1, $isCalled);
        $this->assertEquals($expectedExtracted, $actual);
    }
}

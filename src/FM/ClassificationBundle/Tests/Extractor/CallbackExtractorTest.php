<?php

namespace FM\ClassificationBundle\Tests\Extractor;

use FM\ClassificationBundle\Extractor\CallbackExtractor;

class CallbackExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     */
    public function testExtract()
    {
        $expectedInput = 'foobar';
        $expectedExtracted = 'bar';
        $isCalled = 0;
        $testCase = $this;
        $returnValue = 'some other value';

        $extractorMock = $this->getMockForAbstractClass('FM\ClassificationBundle\Extractor\ExtractorInterface');
        $extractorMock->expects($this->once())
            ->method('extract')
            ->with($expectedInput)
            ->will($this->returnValue($returnValue));

        $callback = function ($input, $extracted, $extractor) use (&$isCalled, $testCase, $returnValue, $expectedExtracted) {
            $isCalled++;

            $testCase->assertEquals($extracted, $returnValue);

            return $expectedExtracted;
        };

        $extractor = new CallbackExtractor($extractorMock, $callback);
        $actual = $extractor->extract($expectedInput);

        $this->assertEquals($expectedExtracted, $actual);
        $this->assertEquals(1, $isCalled);
    }
}

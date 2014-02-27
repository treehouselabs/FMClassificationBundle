<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\ChainExtractor;

class ChainExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expectedInput = 'foobar';
        $expectedExtracted = 'bar';
        $extractorMock = $this->getMockForAbstractClass('FM\ClassificationBundle\Extractor\ExtractorInterface');

        $extractorMock->expects($this->once())
            ->method('extract')
            ->with($expectedInput)
            ->will($this->returnValue($expectedExtracted));

        $extractor = new ChainExtractor([$extractorMock]);
        $actual = $extractor->extract($expectedInput);

        $this->assertEquals($expectedExtracted, $actual);
    }
}

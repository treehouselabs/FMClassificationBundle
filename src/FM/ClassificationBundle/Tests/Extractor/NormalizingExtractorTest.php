<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\NormalizingExtractor;
use FM\ClassificationBundle\Normalizer\LowercaseNormalizer;

class NormalizingExtractorTest extends \PHPUnit_Framework_TestCase
{
    public function testExtract()
    {
        $expectedInput = 'foobar';
        $returnValue = 'UPPERCASED';
        $expectedExtracted = 'uppercased';

        $normalizerMock = new LowercaseNormalizer();
        $extractorMock = $this->getMockForAbstractClass('FM\ClassificationBundle\Extractor\ExtractorInterface');
        $extractorMock->expects($this->once())
            ->method('extract')
            ->with($expectedInput)
            ->will($this->returnValue($returnValue));

        $extractor = new NormalizingExtractor($extractorMock, $normalizerMock);
        $actual    = $extractor->extract($expectedInput);

        $this->assertEquals($expectedExtracted, $actual);
    }
}

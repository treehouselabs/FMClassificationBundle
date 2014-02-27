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

        $callback = function ($input, $extracted, $extractor) use (&$isCalled, $expectedExtracted) {
            $isCalled++;

            return $expectedExtracted;
        };

        $extractor = new CallbackExtractor($callback);
        $actual = $extractor->extract($expectedInput);

        $this->assertEquals($expectedExtracted, $actual);
        $this->assertEquals(1, $isCalled);
    }
}

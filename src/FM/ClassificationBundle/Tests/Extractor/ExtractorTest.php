<?php

namespace FM\ClassificationBundle\Tests\Extractor;

use FM\CascoBundle\Tests\TestCase;
use FM\ClassificationBundle\Extractor\ExtractorInterface;

/**
 * @group functional
 */
abstract class ExtractorTest extends TestCase
{
    /**
     * Tests that extracting a given text results in a given resulting value
     */
    public function testExtraction()
    {
        $matcher = $this->getExtractor();

        foreach ($this->getTestData() as $dataSet) {
            $sourceText = $dataSet[0];
            $expectedValue = $dataSet[1];

            $extractedValue = $matcher->extract($sourceText);

            $this->assertEquals($expectedValue, $extractedValue, "The expected extracted value and actual extracted value do not match");
        }
    }

    /**
     * @return ExtractorInterface
     */
    abstract protected function getExtractor();

    /**
     * @return array
     */
    abstract protected function getTestData();
}

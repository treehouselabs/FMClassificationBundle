<?php

namespace FM\ClassificationBundle\Tests\Extractor;

use FM\ClassificationBundle\Extractor\ExtractorInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group functional
 */
abstract class ExtractorTest extends WebTestCase
{
    /**
     * Tests that extracting a given text results in a given value
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
     * Returns the extractor belonging to the current test
     * It is used to extract from the testing data
     *
     * @return ExtractorInterface
     */
    abstract protected function getExtractor();

    /**
     * Returns (positive) testing data in the following format:
     *
     * [
     *   [
     *     'this is the first source text',
     *     'this is the first (expected) extracted value'
     *   ],
     *   [
     *     'this is the second source text',
     *     'this is the second (expected) extracted value'
     *   ]
     * ]
     *
     * @return array
     */
    abstract protected function getTestData();
}

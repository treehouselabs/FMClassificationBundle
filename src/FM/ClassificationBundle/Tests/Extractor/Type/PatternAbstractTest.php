<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\Type\PatternInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class PatternAbstractTest extends WebTestCase
{
    /**
     * @inheritdoc
     */
    public function testMatch()
    {
        foreach ($this->getTestData() as $dataSet) {
            $pattern = $this->getPattern($dataSet['pattern']);
            $testValue = $dataSet['original'];
            $expected = $dataSet['extracted'];
            $assigned = array_key_exists('assigned', $dataSet) ? $dataSet['assigned'] : null;
            $actual = $pattern->match($testValue, $assigned);

            $this->assertEquals($expected, $actual);
        }
    }

    /**
     * @param $testPattern
     * @return PatternInterface
     */
    abstract protected function getPattern($testPattern);

    /**
     *
     * [
     *   [
     *     'haystack'  => 'John found some pears in the trees down the road, and some pears in the park behind the church.',
     *     'pattern'   => '#\bpear\b#',
     *     'match' => 'pear', 'pear', // if this were a MultiMatch pattern
     *   ],
     *   [
     *     'haystack'  => 'Polly shed some tears about her dog\'s death.',
     *     'pattern'   => '#\shed\b#',
     *     'assigned'  => 'SHED_VALUE',
     *     'extracted' => 'SHED_VALUE', // if this were a SingleMatch pattern
     *   ],
     * ]
     * @return array
     */
    abstract protected function getTestData();
}

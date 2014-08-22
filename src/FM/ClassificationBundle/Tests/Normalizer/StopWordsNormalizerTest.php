<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\StopWordsNormalizer;

class StopWordsNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeIgnoresNull()
    {
        $normalizer = new StopWordsNormalizer(['foo', 'bar']);
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    public function testNormalizeReturnsNull()
    {
        $normalizer = new StopWordsNormalizer([]);
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($stopWords, $value, $expected)
    {
        $normalizer = new StopWordsNormalizer($stopWords);

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            [['hello'], 'Hello  world', ' world'],
            [['wat', 'een'], 'wat een mooi gebouw', 'mooi gebouw'],
            [['B.V.'], 'Financial-media B.V.', 'Financial-media'],
            [['in'], 'omgeving', 'omgeving'],
        ];
    }
}

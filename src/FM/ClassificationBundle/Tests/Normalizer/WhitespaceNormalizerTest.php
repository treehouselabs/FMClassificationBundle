<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\WhitespaceNormalizer;

class WhitespaceNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeIgnoresNull()
    {
        $normalizer = new WhitespaceNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    public function testNormalizeReturnsNull()
    {
        $normalizer = new WhitespaceNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $normalizer = new WhitespaceNormalizer();

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            ['Hello  world', 'Hello world'],
            [' Hello world', 'Hello world'],
            ['      Hello     world    ', 'Hello world'],
            ["\n      Hello     \nworld    ", "Hello world"],
            ["\n      Hello     \nworld    \n\n", "Hello world"],
        ];
    }
}

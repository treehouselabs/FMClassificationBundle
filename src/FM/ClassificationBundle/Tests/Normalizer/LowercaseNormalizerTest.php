<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\LowercaseNormalizer;

class LowercaseNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeIgnoresNull()
    {
        $normalizer = new LowercaseNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    public function testNormalizeReturnsNull()
    {
        $normalizer = new LowercaseNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $normalizer = new LowercaseNormalizer();

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            ['Hello world?', 'hello world?'],
            ['Hello! world?', 'hello! world?'],
            ['ALL CAPS', 'all caps'],
        ];
    }
}

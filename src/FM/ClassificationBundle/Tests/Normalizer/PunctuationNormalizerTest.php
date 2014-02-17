<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\PunctuationNormalizer;

class PunctuationNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeIgnoresNull()
    {
        $normalizer = new PunctuationNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    public function testNormalizeReturnsNull()
    {
        $normalizer = new PunctuationNormalizer();
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $normalizer = new PunctuationNormalizer();

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            ['Hello world?', 'Hello world'],
            ['Hello! world?', 'Hello world'],
            ['Hey, hoe is het?', 'Hey hoe is het'],
            ['Jeroen zei: "Hoe is het eigenlijk?. Piet zei: "Het gaat... uitstekend!', 'Jeroen zei Hoe is het eigenlijk Piet zei Het gaat uitstekend'],
        ];
    }
}

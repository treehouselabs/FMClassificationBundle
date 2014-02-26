<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\ArrayToStringNormalizer;

class ArrayToStringNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $normalizer = new ArrayToStringNormalizer();

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            [
                [
                    1,
                    2,
                    3
                ],
                1
            ],
            [
                new \stdClass(),
                null
            ],
        ];
    }
}

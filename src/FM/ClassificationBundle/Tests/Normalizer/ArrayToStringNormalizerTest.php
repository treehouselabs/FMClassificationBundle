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
            [[1, 2, 3], '123'], // array normalizing
            [[[1], [2], 3 => 3], '123'], // normalizing combination of arrays and scalars
            [new \stdClass(), null], // not an array or string
        ];
    }
}

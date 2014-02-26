<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\BooleanNormalizer;

class BooleanNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $normalizer = new BooleanNormalizer();

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            [null, false],
            [true, true],
            [false, false],
            ['1', true],
            ['0', false],
            [1, true],
            [0, false],
            ['Hello world!', true],
            [[1,2,3], false],
            [new \stdClass(), false],
        ];
    }
}

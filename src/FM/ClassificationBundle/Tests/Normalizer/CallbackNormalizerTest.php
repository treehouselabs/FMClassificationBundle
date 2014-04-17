<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\CallbackNormalizer;

class CallbackNormalizerTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, $expected)
    {
        $callback = function ($value) {
            return strtoupper($value);
        };

        $normalizer = new CallbackNormalizer($callback);

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            ['Hello world?', 'HELLO WORLD?'],
            ['Hello! world?', 'HELLO! WORLD?'],
            ['ALL CAPS', 'ALL CAPS'],
        ];
    }
}

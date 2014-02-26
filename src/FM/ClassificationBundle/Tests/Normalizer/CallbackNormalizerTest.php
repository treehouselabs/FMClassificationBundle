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
        $normalizer = new CallbackNormalizer($value);

        $result = $normalizer->normalize($value);

        $this->assertEquals($expected, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            [
                function ($value) {
                    return 'foobar';
                },
                'foobar',
            ],
            [
                function ($value) {
                    return 123;
                },
                123,
            ],
            [
                function ($value) {
                    return null;
                },
                null,
            ],
        ];
    }
}

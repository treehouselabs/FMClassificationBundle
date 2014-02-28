<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\CallbackNormalizer;

class CallbackNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalize()
    {
        $originalInput = 'FOOBAR';
        $expectedNormalization = 'foobar';
        $isCalled = 0;
        $testCase = $this;

        $callback = function ($input) use (
            &$isCalled,
            $testCase,
            $originalInput,
            $expectedNormalization
        ) {
            $isCalled++;

            $testCase->assertEquals($input, $originalInput);

            return $expectedNormalization;
        };

        $normalizer = new CallbackNormalizer($callback);
        $actual    = $normalizer->normalize($originalInput);

        $this->assertEquals($expectedNormalization, $actual);
        $this->assertEquals(1, $isCalled);
    }
}

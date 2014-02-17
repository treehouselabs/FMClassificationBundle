<?php

namespace FM\ClassificationBundle\Tests\Normalizer;

use FM\ClassificationBundle\Normalizer\ChainNormalizer;

class ChainNormalizerTest extends \PHPUnit_Framework_TestCase
{
    public function testNormalizeIgnoresNull()
    {
        $normalizer = new ChainNormalizer([]);
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    public function testNormalizeReturnsNull()
    {
        $normalizer = new ChainNormalizer([]);
        $result = $normalizer->normalize(null);

        $this->assertEquals(null, $result);
    }

    /**
     * @dataProvider normalizeDataProvider
     */
    public function testNormalize($value, array $expecteds)
    {
        $normalizers = [];

        $output = null;

        foreach ($expecteds as $i => $expected) {
            $input = (0 === $i ? $value : $output);
            $output = $expected;

            $normalizerMock = $this
                ->getMockBuilder('FM\ClassificationBundle\Normalizer\NormalizerInterface')
                ->getMockForAbstractClass();

            $normalizerMock
                ->expects($this->once())
                ->method('normalize')
                ->with($input)
                ->will($this->returnValue($output))
            ;

            $normalizers[] = $normalizerMock;
        }

        $normalizer = new ChainNormalizer($normalizers);

        $result = $normalizer->normalize($value);

        $this->assertEquals($output, $result);
    }

    public function normalizeDataProvider()
    {
        return [
            ['Original value', ['After first normalizer', 'After second normalizer']],
            ['HELLO world?', ['hello world?', 'hello world']],
        ];
    }
}

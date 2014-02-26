<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\NormalizingExtractor;
use FM\ClassificationBundle\Extractor\PatternExtractor;
use FM\ClassificationBundle\Normalizer\ArrayToStringNormalizer;

class NormalizingExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     * @dataProvider getTestData
     */
    public function testExtract($input, $expected)
    {
        $extractor = new NormalizingExtractor($input['extractor'], $input['normalizer']);
        $actual    = $extractor->extract($input['text']);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return [
            [
                [
                    'text'    => 'One foo for man, one giant foo for mankind\b#',
                    'extractor' => new PatternExtractor('#\bfoo\b#'),
                    'normalizer' => new ArrayToStringNormalizer()
                ],
                'foo',
            ],
            [
                [
                    'text'       => 'One foo for man, one giant foo for mankind\b#',
                    'extractor'  => new PatternExtractor('#\bfoobar\b#'),
                    'normalizer' => new ArrayToStringNormalizer()
                ],
                null,
            ],
        ];
    }
}

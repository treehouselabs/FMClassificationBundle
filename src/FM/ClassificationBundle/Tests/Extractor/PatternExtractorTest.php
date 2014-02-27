<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\PatternExtractor;

class PatternExtractorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @inheritdoc
     * @dataProvider getTestData
     */
    public function testExtract($input, $pattern, $expected)
    {
        $extractor = new PatternExtractor($pattern);
        $actual = $extractor->extract($input);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return [
            [
                'One foo for man, one giant foo for mankind',
                '#\bfoo\b#',
                [
                    'foo',
                    'foo'
                ],
            ],
            [
                'The apple does not fall far from the tree',
                '#\bapple\b#',
                [
                    'apple',
                ],
            ],
        ];
    }
}

<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\PatternExtractor;

class PatternExtractorTest extends \PHPUnit_Framework_TestCase
{
    const FAKE_CONSTANT = 'THIS_IS_FAKE';

    /**
     * @inheritdoc
     * @dataProvider getTestData
     */
    public function testExtract($input, $expected)
    {
        $extractor = new PatternExtractor($input['pattern']);
        $default = array_key_exists('default', $input) ? $input['default'] : null;
        $actual = $extractor->extract($input['text'], $default);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return [
            [
                [
                    'text'    => 'One foo for man, one giant foo for mankind\b#',
                    'pattern' => '#\bfoo\b#',
                ],
                [
                    'foo',
                    'foo'
                ],
            ],
            [
                [
                    'text'    => 'One foo for man, one giant bar for mankind\b#',
                    'pattern' => '#\bfoo\b#',
                ],
                ['foo'],
            ],
        ];
    }
}

<?php

namespace FM\ClassificationBundle\Tests\Extractor;

use FM\ClassificationBundle\Extractor\CallbackExtractor;

class CallbackExtractorTest extends \PHPUnit_Framework_TestCase
{
    const FAKE_CONSTANT = 'THIS_IS_FAKE';

    /**
     * @inheritdoc
     * @dataProvider getTestData
     */
    public function testExtract($input, $expected)
    {
        $extractor = new CallbackExtractor($input['callable']);
        $actual    = $extractor->extract($input['text']);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return [
            [
                [
                    'text'     => 'One foo for man, one giant foo for mankind\b#',
                    'callable' => function ($value) {
                        return 'man';
                    },
                ],
                'man',
            ],
            [
                [
                    'text'     => 'One foo for man, one giant foo for mankind\b#',
                    'callable' => function ($value) {
                            return 'giant';
                        },
                ],
                'giant',
            ],
        ];
    }
}

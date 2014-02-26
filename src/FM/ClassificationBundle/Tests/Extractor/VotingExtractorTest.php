<?php

namespace FM\ClassificationBundle\Tests\Extractor\Type;

use FM\ClassificationBundle\Extractor\PatternExtractor;
use FM\ClassificationBundle\Extractor\VotingExtractor;

class VotingExtractorTest extends \PHPUnit_Framework_TestCase
{
    const FAKE_CONSTANT = 'THIS_IS_FAKE';

    /**
     * @dataProvider getTestData
     */
    public function testExtract($input, $expected)
    {
        $extractor = new VotingExtractor($input['extractors'], $input['voter']);
        $actual = $extractor->extract($input['text']);

        $this->assertEquals($expected, $actual);
    }

    public function getTestData()
    {
        return [
            [
                [
                    'extractors' => [
                        new PatternExtractor('#\bapple\b#'),
                        new PatternExtractor('#\bpear\b#'),
                        new PatternExtractor('#\strawberry\b#'),
                    ],
                    'text'    => 'The apple does not fall far from the tree',
                    'voter' => function ($extractedValue) {
                        return $extractedValue;
                    }
                ],
                ['apple'],
            ],
            [
                [
                    'extractors' => [
                        new PatternExtractor('#\bapple\b#'),
                        new PatternExtractor('#\bpear\b#'),
                        new PatternExtractor('#\strawberry\b#'),
                    ],
                    'text'       => 'The apple does not fall far from the tree',
                    'voter' => function ($extractedValue) {
                        return self::FAKE_CONSTANT;
                    }
                ],
                self::FAKE_CONSTANT,
            ],
            [
                [
                    'extractors' => [
                        new PatternExtractor('#\bapple\b#'),
                        new PatternExtractor('#\bpear\b#'),
                        new PatternExtractor('#\strawberry\b#'),
                    ],
                    'text'       => 'The tomato does not fall far from the tree',
                    'voter' => function ($extractedValue, $text) {
                        return self::FAKE_CONSTANT;
                    }
                ],
                null,
            ],
        ];
    }
}

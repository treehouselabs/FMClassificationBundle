<?php

namespace FM\ClassificationBundle\Tests\PatternAbstractTest\Type\Pattern;

use FM\ClassificationBundle\Extractor\Type\Pattern\SingleMatchPattern;
use FM\ClassificationBundle\Tests\Extractor\Type\PatternAbstractTest;

class SingleMatchPatternTest extends PatternAbstractTest
{
    /**
     * @inheritdoc
     */
    protected function getPattern($testPattern)
    {
        return new SingleMatchPattern($testPattern);
    }

    /**
     * @inheritdoc
     */
    protected function getTestData()
    {
        return [
            [
                'original'  => 'Jimmy says an apple does not fall far from the tree, but Billy says it could.',
                'pattern'   => '#\ban apple does not fall far from the tree\b#',
                'assigned'  => 'TRANSLATED_VALUE',
                'extracted' => 'TRANSLATED_VALUE',
            ],
            [
                'original'  => 'Polly saw someone laughing like a farmer with a toothache.',
                'pattern'   => '#\blaughing like a farmer with a toothache\b#',
                'assigned'  => 'TRANSLATED_VALUE2',
                'extracted' => 'TRANSLATED_VALUE2',
            ],
        ];
    }
}

<?php

namespace FM\ClassificationBundle\Tests\PatternAbstractTest\Type\Pattern;

use FM\ClassificationBundle\Extractor\Type\Pattern\MultiMatchPattern;
use FM\ClassificationBundle\Tests\Extractor\Type\PatternAbstractTest;

class MultiMatchPatternTest extends PatternAbstractTest
{
    /**
     * @inheritdoc
     */
    protected function getPattern($testPattern)
    {
        return new MultiMatchPattern($testPattern);
    }

    /**
     * @inheritdoc
     */
    protected function getTestData()
    {
        return [
            [
                'original'  => 'John found some pears in the trees down the road, and some pears in the park behind the church.',
                'pattern'   => 'pear',
                'extracted' => ['pear', 'pear'],
            ],
        ];
    }
}

<?php

namespace FM\ClassificationBundle\Tests\Classifier;

use FM\ClassificationBundle\Classifier\ResultPersistingClassifier;

class ResultPersistingClassifierTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $baseClassifier = $this->getMockBuilder('FM\ClassificationBundle\Classifier\BayesClassifier')
            ->disableOriginalConstructor()
            ->getMock();

        $doctrine = $this->getMockBuilder('Doctrine\Bundle\DoctrineBundle\Registry')
            ->disableOriginalConstructor()
            ->getMock();

        $classifier = new ResultPersistingClassifier($baseClassifier, $doctrine, 'test');

        $this->assertInstanceOf('FM\ClassificationBundle\Classifier\ResultPersistingClassifier', $classifier);
    }
}

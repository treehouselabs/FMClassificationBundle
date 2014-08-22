<?php

namespace FM\ClassificationBundle\Tests\Classifier;

use FM\ClassificationBundle\Classifier\BayesClassifier;
use FM\ClassificationBundle\DataSource\ArrayDataSource;

class BayesClassifierTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $baseClassifier = $this->getMockBuilder('Fieg\Bayes\Classifier')
            ->disableOriginalConstructor()
            ->getMock();

        $classifier = new BayesClassifier($baseClassifier);

        $this->assertInstanceOf('FM\ClassificationBundle\Classifier\BayesClassifier', $classifier);
    }

    /**
     * @dataProvider classifyDataProvider
     *
     * @param $baseClassifierResult
     * @param $expectedConfidence
     * @param $expectedReturnValue
     */
    public function testClassifyCallsBaseClassifier($baseClassifierResult, $expectedConfidence, $expectedReturnValue)
    {
        $baseClassifier = $this->getMockBuilder('Fieg\Bayes\Classifier')
            ->disableOriginalConstructor()
            ->getMock();

        $baseClassifier
            ->expects($this->once())
            ->method('classify')
            ->with('test')
            ->will($this->returnValue($baseClassifierResult));

        $classifier = new BayesClassifier($baseClassifier);

        $confidence = null;

        $result = $classifier->classify('test', $confidence);

        $this->assertEquals($expectedConfidence, $confidence);
        $this->assertEquals($expectedReturnValue, $result);
    }

    public function classifyDataProvider()
    {
        return [
            [['key1' => 0.2133333], 0.2133333, 'key1'],
            [[]                   , 0        , null],
        ];
    }

    public function testTrain()
    {
        $baseClassifier = $this->getMockBuilder('Fieg\Bayes\Classifier')
            ->disableOriginalConstructor()
            ->getMock();

        $baseClassifier
            ->expects($this->once())
            ->method('train')
            ->with('title', 'label');

        $classifier = new BayesClassifier($baseClassifier);

        $classifier->train(new ArrayDataSource([
            ['label', 'title', 1],
        ]));
    }
}

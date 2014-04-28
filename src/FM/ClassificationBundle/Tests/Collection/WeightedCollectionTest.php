<?php

namespace FM\ClassificationBundle\Tests\Collection;

use FM\ClassificationBundle\Collection\WeightedCollection;

class ScoredCollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WeightedCollection
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new WeightedCollection();
    }

    public function testConstructor()
    {
        $collection = $this->subject;

        $this->assertCount(0, $collection->all(), "A new collection starts empty");

        return $collection;
    }

    public function testAdd()
    {
        $collection = $this->subject;

        $object1 = (object) ['name' => 'object1'];

        $collection->add($object1, 0.5);

        $this->assertCount(1, $collection->all());
    }

    /**
     * @expectedException \OutOfBoundsException
     * @dataProvider outOfBoundScoreDataProvider
     */
    public function testAddOnlyAcceptScoreBetweenZeroAndOne($value)
    {
        $collection = $this->subject;

        $object1 = (object) ['name' => 'object1'];

        $collection->add($object1, $value);
    }

    public function outOfBoundScoreDataProvider()
    {
        return [
            [1.2],
            [-0.4]
        ];
    }

    /**
     * @depends testConstructor
     */
    public function testCountReturnsNumberOfItemsInCollection()
    {
        $collection = $this->subject;

        $object1 = (object) ['name' => 'object1'];
        $object2 = (object) ['name' => 'object2'];

        $collection->add($object1, 0.5);
        $collection->add($object2, 0.6);

        $this->assertEquals(2, $collection->count());
    }

    public function testMergeAddsNewItems()
    {
        $object1 = (object) ['name' => 'object1'];
        $object2 = (object) ['name' => 'object2'];
        $object3 = (object) ['name' => 'object3'];
        $object4 = (object) ['name' => 'object4'];

        $collection1 = new WeightedCollection();

        $collection1->add($object1, 1);
        $collection1->add($object2, 0.5);
        $collection1->add($object3, 0.1);

        $collection2 = new WeightedCollection();
        $collection2->add($object4, 0.6);

        $collection1->merge($collection2);

        $this->assertSame([$object1, $object4, $object2, $object3], $collection1->all());
    }

    public function testMergeSumsExistingItems()
    {
        $object1 = (object) ['name' => 'object1'];
        $object2 = (object) ['name' => 'object2'];
        $object3 = (object) ['name' => 'object3'];
        $object4 = (object) ['name' => 'object4'];

        $collection1 = new WeightedCollection();

        $collection1->add($object1, 1);
        $collection1->add($object2, 0.5);
        $collection1->add($object3, 0.1);

        $collection2 = new WeightedCollection();
        $collection2->add($object3, 0.6); // added to 0.1
        $collection2->add($object4, 0.6);

        $collection1->merge($collection2);

        $this->assertSame(
            [
                [$object1, 1.0],
                [$object3, 0.7],
                [$object4, 0.6],
                [$object2, 0.5],
            ],
            $collection1->raw()
        );
    }

    public function testMergeSumsExistingItems2()
    {
        $object1 = (object) ['name' => 'object1'];
        $object2 = (object) ['name' => 'object2'];
        $object3 = (object) ['name' => 'object3'];
        $object4 = (object) ['name' => 'object4'];
        $object5 = (object) ['name' => 'object5'];

        $collection1 = new WeightedCollection();

        $collection1->add($object1, 0.6);
        $collection1->add($object2, 0.3);
        $collection1->add($object3, 0.75);
        $collection1->add($object4, 0.8);
        $collection1->add($object5, 0.4);

        $collection2 = new WeightedCollection();
        $collection2->add($object1, 0.74);
        $collection2->add($object2, 0.2);
        $collection2->add($object3, 0.5);
        // object4 deliberately not in set
        $collection2->add($object5, 0.4);

        $collection1->merge($collection2);

        $this->assertGreaterThanOrEqual(0.8, $collection1->topScore());
        $this->assertEquals($object1, $collection1->top());
    }

    public function testMap()
    {
        $object1 = (object) ['name' => 'object1'];
        $object2 = (object) ['name' => 'object2'];
        $object3 = (object) ['name' => 'object3'];

        $collection1 = new WeightedCollection();

        $collection1->add($object1, 1);
        $collection1->add($object2, 0.5);
        $collection1->add($object3, 0.1);

        $collection1->map(function ($value) { return strtoupper($value->name); });

        $this->assertEquals([
                'OBJECT1',
                'OBJECT2',
                'OBJECT3',
            ],
            $collection1->all()
        );
    }
}

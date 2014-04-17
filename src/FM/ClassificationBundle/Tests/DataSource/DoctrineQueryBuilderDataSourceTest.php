<?php

namespace FM\ClassificationBundle\Tests\DataSource;

use FM\ClassificationBundle\DataSource\DoctrineQueryBuilderDataSource;

class DoctrineQueryBuilderDataSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $queryBuilderMock = $this->getMockBuilder('Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $dataSource = new DoctrineQueryBuilderDataSource($queryBuilderMock);

        $this->assertInstanceOf('FM\ClassificationBundle\DataSource\DoctrineQueryBuilderDataSource', $dataSource);
    }

    public function testIterate()
    {
        $data = [['first'], ['second'], ['third']];

        $queryMock = $this->getMockBuilder('\Doctrine\ORM\AbstractQuery')
            ->disableOriginalConstructor()
            ->setMethods(['iterate'])
            ->getMockForAbstractClass();

        $queryMock
            ->expects($this->any())
            ->method('iterate')
            ->will($this->returnValue(new \ArrayIterator($data)));

        $emMock = $this->getMockBuilder('\Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $emMock
            ->expects($this->any())
            ->method('detach');

        $queryBuilderMock = $this->getMockBuilder('\Doctrine\ORM\QueryBuilder')
            ->disableOriginalConstructor()
            ->getMock();

        $queryBuilderMock
            ->expects($this->any())
            ->method('getQuery')
            ->will($this->returnValue($queryMock));

        $queryBuilderMock
            ->expects($this->any())
            ->method('getEntityManager')
            ->will($this->returnValue($emMock));

        $dataSource = new DoctrineQueryBuilderDataSource($queryBuilderMock);

        foreach ($dataSource as $item) {
            $this->assertInternalType('string', $item);
        }

        $this->assertEquals(['first', 'second', 'third'], iterator_to_array($dataSource));
    }
}

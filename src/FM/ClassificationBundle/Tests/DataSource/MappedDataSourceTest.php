<?php

namespace FM\ClassificationBundle\Tests\DataSource;

use FM\ClassificationBundle\DataSource\ArrayDataSource;
use FM\ClassificationBundle\DataSource\MappedDataSource;

class MappedDataSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $dataSource = $this->getMockBuilder('FM\ClassificationBundle\DataSource\DataSourceInterface')
            ->getMockForAbstractClass();

        $dataSource = new MappedDataSource($dataSource, function($item) { return $item; });

        $this->assertInstanceOf('FM\ClassificationBundle\DataSource\MappedDataSource', $dataSource);
    }

    public function testIterate()
    {
        $array = ['first', 'second', 'third'];

        $dataSource = new ArrayDataSource($array);

        $mapper = function($item) {
            return strtoupper($item);
        };

        $dataSource = new MappedDataSource($dataSource, $mapper);

        $index = 0;

        foreach ($dataSource as $item) {
            $this->assertEquals($mapper($array[$index++]), $item);
        }
    }

    public function testUnmap()
    {
        $array = ['first', 'second', 'third'];

        $dataSource = new ArrayDataSource($array);

        $mapper = function($item) {
            return strtoupper($item);
        };

        $dataSource = new MappedDataSource($dataSource, $mapper);

        $index = 0;

        foreach ($dataSource as $item) {
            $this->assertEquals($array[$index++], $dataSource->unmap($item));
        }
    }
}

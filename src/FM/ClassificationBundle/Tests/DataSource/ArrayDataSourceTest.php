<?php

namespace FM\ClassificationBundle\Tests\DataSource;

use FM\ClassificationBundle\DataSource\ArrayDataSource;

class ArrayDataSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $dataSource = new ArrayDataSource(['first', 'second', 'third']);

        $this->assertInstanceOf('FM\ClassificationBundle\DataSource\ArrayDataSource', $dataSource);
    }

    public function testIterate()
    {
        $array = ['first', 'second', 'third'];

        $dataSource = new ArrayDataSource($array);

        $index = 0;

        foreach ($dataSource as $item) {
            $this->assertEquals($array[$index++], $item);
        }
    }
}

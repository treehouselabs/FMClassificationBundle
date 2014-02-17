<?php

namespace FM\ClassificationBundle\Tests\DataSource;

use FM\ClassificationBundle\DataSource\ArrayDataSource;
use FM\ClassificationBundle\DataSource\FilteredDataSource;

class FilteredDataSourceTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructor()
    {
        $dataSource = $this->getMockBuilder('FM\ClassificationBundle\DataSource\DataSourceInterface')
            ->getMockForAbstractClass();

        $dataSource = new FilteredDataSource($dataSource, function($item) { return true; });

        $this->assertInstanceOf('FM\ClassificationBundle\DataSource\FilteredDataSource', $dataSource);
    }

    public function testIterate()
    {
        $array = ['first', 'second', 'third'];

        $dataSource = new ArrayDataSource($array);

        $filter = function($item) {
            return ('second' !== strtolower($item));
        };

        $dataSource = new FilteredDataSource($dataSource, $filter);

        $result = [];

        foreach ($dataSource as $item) {
            $result[] = $item;
        }

        $this->assertNotContains('second', $result);
    }
}

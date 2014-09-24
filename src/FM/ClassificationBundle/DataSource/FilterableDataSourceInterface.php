<?php

namespace FM\ClassificationBundle\DataSource;

interface FilterableDataSourceInterface extends DataSourceInterface
{
    /**
     * @param $value
     *
     * @return mixed
     */
    public function filter($value);
}

<?php

namespace FM\ClassificationBundle\DataSource;

class ArrayDataSource implements DataSourceInterface
{
    /**
     * @var array
     */
    protected $array;

    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * Constructor.
     *
     * @param array $array
     */
    public function __construct(array $array)
    {
        $this->array = $array;
    }

    /**
     * @return \Iterator
     */
    public function getInnerIterator()
    {
        return $this->iterator ?: $this->iterator = new \ArrayIterator($this->array);
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        return $this->getInnerIterator()->current();
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->getInnerIterator()->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->getInnerIterator()->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        return $this->getInnerIterator()->valid();
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->getInnerIterator()->rewind();
    }
}

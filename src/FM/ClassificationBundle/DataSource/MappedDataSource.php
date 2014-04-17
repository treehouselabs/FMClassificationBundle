<?php

namespace FM\ClassificationBundle\DataSource;

/**
 * Maps a DataSource with a mapper callback. Allows unmapping to get the
 * original value
 */
class MappedDataSource implements DataSourceInterface
{
    /**
     * @var DataSourceInterface
     */
    protected $dataSource;

    /**
     * @var callable
     */
    protected $mapper;

    /**
     * @var \Iterator
     */
    protected $iterator;

    /**
     * @var array
     */
    protected $cache = array();

    /**
     * Cache is complete when we iterated one complete time over the original data source
     *
     * @var bool
     */
    protected $cacheComplete = false;

    /**
     * Constructor.
     */
    public function __construct(DataSourceInterface $dataSource, \Closure $mapper)
    {
        $this->dataSource = $dataSource;
        $this->mapper = $mapper;
    }

    /**
     * Returns the original value for a mapped value
     *
     * @param mixed $value
     * @return mixed|null
     */
    public function unmap($value)
    {
        $mapper = $this->mapper;

        foreach ($this->dataSource as $current) {
            $mapped = $mapper($current);

            if ($mapped === $value) {
                return $current;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->getIterator()->next();
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $mapper = $this->mapper;

        $value = $this->getIterator()->current();

        if ($this->cacheComplete) {
            return $value;
        }

        if ($value) {
            $this->cache[] = $mappedValue = $mapper($value);

            return $mappedValue;
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->getIterator()->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $valid = $this->getIterator()->valid();

        if (!$valid) {
            $this->cacheComplete = true;

            $this->iterator = null;
        }

        return $valid;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        if (!$this->cacheComplete) {
            $this->cache = [];
        }

        $this->dataSource->rewind();
    }

    /**
     * Automatically switches to cached version when iterated one time
     *
     * @return \ArrayIterator|DataSourceInterface
     */
    protected function getIterator()
    {
        if ($this->iterator) {
            return $this->iterator;
        }

        if ($this->cacheComplete) {
            $this->iterator = new \ArrayIterator($this->cache);
        } else {
            $this->iterator = $this->dataSource;
        }

        return $this->iterator;
    }
}

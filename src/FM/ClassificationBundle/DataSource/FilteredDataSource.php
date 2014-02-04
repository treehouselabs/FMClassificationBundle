<?php

namespace FM\ClassificationBundle\DataSource;

/**
 * Takes an existing data source and filters it's item using a given
 * callback function.
 */
class FilteredDataSource implements DataSourceInterface
{
    protected $dataSource;
    protected $filterCallback;

    /**
     * Constructor.
     *
     * @param DataSourceInterface $dataSource
     * @param callable $filterCallback
     */
    public function __construct(DataSourceInterface $dataSource, \Closure $filterCallback)
    {
        $this->dataSource = $dataSource;
        $this->filterCallback = $filterCallback;
    }

    /**
     * {@inheritdoc}
     */
    public function current()
    {
        $callback = $this->filterCallback;

        $value = $this->dataSource->current();

        if ($callback($value)) {
            return $value;
        } else {
            $this->dataSource->next();

            return $this->current();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        $this->dataSource->next();
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->dataSource->key();
    }

    /**
     * {@inheritdoc}
     */
    public function valid()
    {
        $callback = $this->filterCallback;

        $valid = $this->dataSource->valid();

        if (!$valid) {
            return false;
        }

        $current = $this->dataSource->current();

        if ($callback($current)) {
            return true;
        } else {
            $this->dataSource->next();

            return $this->valid();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->dataSource->rewind();
    }
}

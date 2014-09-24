<?php

namespace FM\ClassificationBundle\DataSource;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class DoctrineQueryBuilderDataSource implements DataSourceInterface, FilterableDataSourceInterface
{
    protected $qb;
    protected $iterator;
    protected $hydrationMode;
    protected $filterCallback;

    /**
     * Constructor.
     *
     * @param QueryBuilder $qb
     * @param int $hydrationMode
     */
    public function __construct(QueryBuilder $qb, $hydrationMode = AbstractQuery::HYDRATE_OBJECT)
    {
        $this->qb = $qb;
        $this->hydrationMode = $hydrationMode;
    }

    /**
     * @param callable|null $filterCallback
     *
     * @return $this
     */
    public function setFilterCallback(\Closure $filterCallback = null)
    {
        $this->filterCallback = $filterCallback;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasFilterCallback()
    {
        return (null !== $this->filterCallback);
    }

    /**
     * {@inheritdoc}
     */
    public function filter($value)
    {
        if (true === $this->hasFilterCallback()) {
            $this->qb = call_user_func_array($this->filterCallback, [$this->qb, $value]);
        }
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
    public function current()
    {
        if (false !== $row = $this->getInnerIterator()->current()) {
            return current($row);
        }

        return null;
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
     * Attention: this will force the query to be executed again
     *
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->iterator = null;

        $this->getInnerIterator()->rewind();
    }

    /**
     * @return \Doctrine\ORM\Internal\Hydration\IterableResult
     */
    protected function getInnerIterator()
    {
        if (null === $this->iterator) {
            $query = $this->qb->getQuery();

            $this->iterator = $query->iterate(null, $this->hydrationMode);
        }

        return $this->iterator;
    }
}

<?php

namespace FM\ClassificationBundle\DataSource;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;

class DoctrineQueryBuilderDataSource implements DataSourceInterface
{
    protected $qb;
    protected $iterator;
    protected $hydrationMode;

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
            return $row[0];
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
     */
    public function rewind()
    {
        $this->iterator = null;

        $this->getInnerIterator()->rewind();
    }

    protected function getInnerIterator()
    {
        if (null === $this->iterator) {
            $query = $this->qb->getQuery();

            $this->iterator = $query->iterate(null, $this->hydrationMode);
        }

        return $this->iterator;
    }
}

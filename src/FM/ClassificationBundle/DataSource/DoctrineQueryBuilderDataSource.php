<?php

namespace FM\ClassificationBundle\DataSource;

use Doctrine\ORM\QueryBuilder;

class DoctrineQueryBuilderDataSource implements DataSourceInterface
{
    protected $qb;
    protected $iterator;
    protected $previousRecord;

    /**
     * Constructor.
     *
     * @param QueryBuilder $qb
     */
    public function __construct(QueryBuilder $qb)
    {
        $this->qb = $qb;
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
        if (null !== $this->previousRecord) {
            $this->qb->getEntityManager()->detach($this->previousRecord);
        }

        if (false !== $row = $this->getInnerIterator()->current()) {
            $this->previousRecord = $row[0];

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
        return $this->iterator ?: $this->iterator = $this->qb->getQuery()->iterate();
    }
}

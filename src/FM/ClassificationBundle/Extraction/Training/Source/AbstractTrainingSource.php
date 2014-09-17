<?php

namespace FM\ClassificationBundle\Extraction\Training\Source;

use FM\ClassificationBundle\DataSource\DoctrineQueryBuilderDataSource;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\ORM\QueryBuilder;

abstract class AbstractTrainingSource extends DoctrineQueryBuilderDataSource
{
    /**
     * @param RegistryInterface $register
     */
    public function __construct(RegistryInterface $register)
    {
        parent::__construct($this->getQueryBuilder($register), $this->getHydrationMode());
    }

    /*
     * @param int|null $hydrationMode
     */
    abstract public function getHydrationMode();

    /**
     * @param RegistryInterface $register
     *
     * @return QueryBuilder
     */
    abstract public function getQueryBuilder(RegistryInterface $register);

    /**
     * @param int $limit
     */
    public function setTrainingLimit($limit)
    {
        $this->qb->setMaxResults($limit);
    }

    /**
     * @return int
     */
    public function getTrainingLimit()
    {
        return $this->qb->getMaxResults();
    }
}

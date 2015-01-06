<?php

namespace FM\ClassificationBundle\Classifier;

use Doctrine\ORM\EntityRepository;
use FM\ClassificationBundle\DataSource\DataSourceInterface;
use FM\ClassificationBundle\Entity\ClassifyResult;
use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;

/**
 * Wrapping Classifier that stores the result of the input-output-transformation in the database
 */
class ResultPersistingClassifier implements TrainableClassifierInterface
{
    /**
     * @var ClassifierInterface
     */
    protected $classifier;

    /**
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    protected $doctrine;

    /**
     * @var string
     */
    protected $classifierName;

    /**
     * Constructor.
     *
     * @param ClassifierInterface $classifier
     * @param Doctrine            $doctrine
     * @param string              $classifierName discriminator to store results of multiple classifiers
     */
    public function __construct(ClassifierInterface $classifier, Doctrine $doctrine, $classifierName)
    {
        $this->classifier = $classifier;
        $this->doctrine = $doctrine;
        $this->classifierName = $classifierName;
    }

    /**
     * {@inheritdoc}
     */
    public function classify($input, &$confidence = null)
    {
        $output = $this->classifier->classify($input, $confidence);

        $this->persistResult($input, $output, $confidence);

        return $output;
    }

    /**
     * Pass-through to original classifier
     *
     * @param DataSourceInterface $dataSource
     */
    public function train(DataSourceInterface $dataSource)
    {
        if ($this->classifier instanceof TrainableClassifierInterface) {
            $this->classifier->train($dataSource);
        }
    }

    /**
     * @return mixed
     */
    public function getClassifierName()
    {
        return $this->classifierName;
    }

    /**
     * Persists the result
     *
     * @param mixed       $input
     * @param mixed       $output
     * @param float       $confidence
     * @param null|string $expected
     */
    protected function persistResult($input, $output, $confidence, $expected = null)
    {
        if (null === $this->doctrine) {
            return;
        }

        $classifyResult = new ClassifyResult();
        $classifyResult->setInput($input);
        $classifyResult->setOutput($output);
        $classifyResult->setScore(round($confidence, 3));
        $classifyResult->setClassifier($this->classifierName);
        $classifyResult->setExpected($expected);

        if (null !== $expected) {
            $classifyResult->setDatetimeTrained(new \DateTime());
        }

        // if we have an existing record, update it's score, otherwise insert
        if ($existingClassifyResult = $this->doctrine->getRepository('FMClassificationBundle:ClassifyResult')->findOneByHash($classifyResult->getHash())) {
            $existingClassifyResult->setOutput($output);
            $existingClassifyResult->setScore(round($confidence, 3));
            $existingClassifyResult->incrementHits();

            $this->doctrine->getManager()->flush($existingClassifyResult);
        } else {
            $this->doctrine->getManager()->persist($classifyResult);
            $this->doctrine->getManager()->flush($classifyResult);
        }
    }

    /**
     * @param array $input
     * @param mixed $expected
     */
    public function teach($input, $expected)
    {
        /** @var EntityRepository $repository */
        $repository = $this->doctrine->getRepository('FMClassificationBundle:ClassifyResult');

        $hash = ClassifyResult::generateHash($input, $this->getClassifierName());

        /** @var ClassifyResult[] $classifyResults */
        $classifyResults = $repository->createQueryBuilder('cr')
            ->where('cr.hash = :hash')
            ->andWhere('cr.classifier = :classifier')
            ->setParameter(':hash', $hash)
            ->setParameter(':classifier', $this->getClassifierName())
            ->getQuery()
            ->getResult();

        if (count($classifyResults) > 0) {
            foreach ($classifyResults as $classifyResult) {
                $classifyResult->setExpected($expected);

                $classifyResult->incrementWeight();

                $classifyResult->setScore(null);

                $classifyResult->setDatetimeTrained(new \DateTime());
            }

            $this->doctrine->getManager()->flush($classifyResults);
        } else {
            $this->persistResult($input, null, null, $expected);
        }
    }
}

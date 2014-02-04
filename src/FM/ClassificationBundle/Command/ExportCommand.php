<?php

namespace FM\ClassificationBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityRepository;
use FM\ClassificationBundle\Entity\ClassifyResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ExportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('classification:export');
        $this->setDescription('Exports trained ClassifyResults to fixtures');
        $this->addArgument('path', null, 'Path where the fixtures should be stored');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Doctrine $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');

        /** @var EntityRepository $repo */
        $repo = $doctrine->getRepository('FMClassificationBundle:ClassifyResult');

        $path = rtrim($input->getArgument('path'), '/');
        if (!is_dir($path)) {
            throw new \InvalidArgumentException(sprintf('Path "%s" isn\'t a valid directory', $path));
        }

        $qb = $repo->createQueryBuilder('cr')->where('cr.expected IS NOT NULL');

        /** @var ClassifyResult[] $classifyResults */
        $classifyResults = $qb->getQuery()->getResult();

        $output = [];

        foreach ($classifyResults as $classifyResult) {
            $output['classify' . $classifyResult->getId()] = array(
              'input' => $classifyResult->getInput(),
              'expected' => $classifyResult->getExpected(),
              'classifier' => $classifyResult->getClassifier(),
            );
        }

        $output = array('FM\ClassificationBundle\Entity\ClassifyResult' => $output);

        file_put_contents($path . '/classify_result.yml', Yaml::dump($output));
    }
}

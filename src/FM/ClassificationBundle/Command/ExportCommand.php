<?php

namespace FM\ClassificationBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityRepository;
use FM\ClassificationBundle\Entity\ClassifyResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ExportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('classification:export');
        $this->setDescription('Exports trained ClassifyResults to fixtures');
        $this->addArgument('path', null, 'Path where the fixtures should be stored');
        $this->addOption('classifier', null, InputOption::VALUE_OPTIONAL, 'Name of the classifier');
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

        $file = $path . '/classify_result.yml';

        $qb = $repo->createQueryBuilder('cr')->where('cr.expected IS NOT NULL');

        if ($input->getOption('classifier')) {
            $qb->andWhere('cr.classifier = :classifier');
            $qb->setParameter('classifier', $input->getOption('classifier'));

            $file = $path . '/'.$input->getOption('classifier').'.yml';
        }

        /** @var ClassifyResult[] $classifyResults */
        $classifyResults = $qb->getQuery()->getResult();

        $output = [];
        $uniques = [];

        foreach ($classifyResults as $classifyResult) {
            $hash = md5(json_encode($classifyResult->getInput()) . $classifyResult->getExpected());

            if (isset($uniques[$hash])) {
                continue;
            }

            $output['classify' . $classifyResult->getId()] = array(
              'input' => $classifyResult->getInput(),
              'expected' => $classifyResult->getExpected(),
              'classifier' => $classifyResult->getClassifier(),
              'weight' => $classifyResult->getWeight(),
            );

            $uniques[$hash] = 1;
        }

        $output = array('FM\ClassificationBundle\Entity\ClassifyResult' => $output);

        file_put_contents($file, Yaml::dump($output));
    }
}

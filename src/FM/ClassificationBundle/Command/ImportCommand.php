<?php

namespace FM\ClassificationBundle\Command;

use Doctrine\Bundle\DoctrineBundle\Registry as Doctrine;
use Doctrine\ORM\EntityRepository;
use FM\ClassificationBundle\Entity\ClassifyResult;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;

class ImportCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('classification:import');
        $this->addArgument('file', null, 'Fixture file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var Doctrine $doctrine */
        $doctrine = $this->getContainer()->get('doctrine');

        /** @var EntityRepository $repo */
        $repo = $doctrine->getRepository('FMClassificationBundle:ClassifyResult');

        $file = $input->getArgument('file');
        if (!file_exists($file)) {
            throw new \InvalidArgumentException(sprintf('File "%s" isn\'t found', $file));
        }

        $input = Yaml::parse(file_get_contents($file));

        foreach ($input['FM\ClassificationBundle\Entity\ClassifyResult'] as $classifyResultData) {
            $classifyResult = new ClassifyResult();
            $classifyResult->setInput($classifyResultData['input']);
            if (null !== $classifyResultData['expected']) {
                $classifyResult->setExpected($classifyResultData['expected']);
            }
            $classifyResult->setClassifier($classifyResultData['classifier']);

            if ($existingClassifyResult = $repo->findOneByHash($classifyResult->getHash())) {
                $doctrine->getManager()->remove($existingClassifyResult);
                $doctrine->getManager()->flush();
            }

            $doctrine->getManager()->persist($classifyResult);
        }

        $doctrine->getManager()->flush();
    }
}

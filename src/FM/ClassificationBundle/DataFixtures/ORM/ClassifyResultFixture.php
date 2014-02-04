<?php

namespace FM\ClassificationBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Symfony\Component\Finder\Finder;

/**
 * Load classify result fixtures that were exported with the
 * ExportCommand
 */
class ClassifyResultFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $loader = new \Nelmio\Alice\Loader\Yaml();

        $finder = new Finder();
        $fixtures = $finder
            ->in(__DIR__. '/../../Resources/fixtures/')
            ->files()
            ->name('*.yml')
            ->sortByName()
        ;

        foreach ($fixtures as $file) {
            $objects = $loader->load($file);

            $this->loadObjects($manager, $objects);
        }

        $manager->flush();
    }

    protected function loadObjects(ObjectManager $manager, $objects)
    {
        $persister = new \Nelmio\Alice\ORM\Doctrine($manager);
        $persister->persist($objects);
    }
}

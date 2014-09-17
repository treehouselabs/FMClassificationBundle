<?php

namespace FM\ClassificationBundle\Extractor\Storage;

interface StoreableInterface
{
    /**
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function store();

    /**
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function load();
}

<?php

namespace FM\ClassificationBundle\Extraction\Storage;

interface StoreableInterface
{
    /**
     * @param StorageInterface $storage
     */
    public function setStorage(StorageInterface $storage);

    /**
     * @return StorageInterface
     */
    public function getStorage();

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

<?php

namespace FM\ClassificationBundle\Extractor\Storage;

interface StorageInterface
{
    /**
     * @param string $id
     * @param array  $data
     *
     * @return string The id
     */
    public function store(array $data, $id = null);

    /**
     * @param string $id
     *
     * @return array The loaded data
     */
    public function load($id);

    /**
     * @param string $id
     *
     * @return boolean
     */
    public function has($id);
}

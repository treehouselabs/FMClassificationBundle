<?php

namespace FM\ClassificationBundle\Extractor\Storage;

class FileStorage implements StorageInterface
{
    /**
     * @var string
     */
    protected $storageDirectory;

    /**
     * @param string $storageDirectory
     */
    public function __construct($storageDirectory)
    {
        if (null === $storageDirectory) {
            $storageDirectory = sys_get_temp_dir() . '/extractor_storage';
        }

        if (!is_dir($storageDirectory)) {
            mkdir($storageDirectory, 0777, true);
        }

        $this->storageDirectory = $storageDirectory;
    }

    /**
     * @inheritdoc
     */
    public function store(array $data, $id = null)
    {
        if ($id) {
            $filePath = $this->getFilePath($id);
        } else {
            do {
                $id = sha1($data . rand(0,9999) . time());
                $filePath = $this->getFilePath($id);
            } while (file_exists($filePath));
        }

        file_put_contents($filePath, $data);

        return $id;
    }

    /**
     * @inheritdoc
     */
    public function load($id)
    {
        $filePath = $this->getFilePath($id);

        return @file_get_contents($filePath);
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return file_exists($this->getFilePath($id));
    }

    /**
     * Returns path for file id
     *
     * @param string $id
     *
     * @return string
     */
    protected function getFilePath($id)
    {
        return $this->storageDirectory . '/' . $id;
    }
}

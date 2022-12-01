<?php

namespace Slince\Upload\Filesystem;

use \RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Local implements FilesystemInterface
{
    /**
     * @var string
     */
    protected $savePath;

    public function __construct(string $savePath)
    {
        if (!is_dir($savePath)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is invalid', $savePath));
        }
        $this->savePath = rtrim($savePath, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function upload(string $key, UploadedFile $file, bool $overwrite = false)
    {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath) && !$overwrite) {
            throw new RuntimeException(sprintf('The file with key "%s" is exists.', $key));
        }
        return $file->move(dirname($filePath), basename($filePath));
    }

    protected function getFilePath(string $key): string
    {
        return "{$this->savePath}/{$key}";
    }
}

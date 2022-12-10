<?php

namespace Slince\Upload\Filesystem;

use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Local implements FilesystemInterface
{
    /**
     * @var string
     */
    protected $savePath;

    public function __construct(string $savePath)
    {
        $this->savePath = rtrim($savePath, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function upload(string $key, UploadedFile $file, bool $overwrite = false)
    {
        if (!$this->ensureDirectory($this->savePath)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" was not created', $this->savePath));
        }

        if (!is_writable($this->savePath)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is invalid', $this->savePath));
        }

        $filePath = $this->getFilePath($key);
        if (!$overwrite && file_exists($filePath)) {
            throw new RuntimeException(sprintf('The file with key "%s" is exists.', $key));
        }

        return $file->move(dirname($filePath), basename($filePath));
    }

    protected function getFilePath(string $key): string
    {
        return $this->savePath . DIRECTORY_SEPARATOR . $key;
    }

    protected function ensureDirectory(string $directory): bool
    {
        return is_dir($directory) || mkdir($directory, 0755, true) || is_dir($directory);
    }
}

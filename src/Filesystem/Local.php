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
        if (!$this->ensureDirectory($savePath)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" was not created', $savePath));
        }

        if (!is_writable($savePath)) {
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

    /**
     * {@inheritdoc}
     */
    public function delete(string $key): bool
    {
        $filePath = $this->getFilePath($key);

        if (file_exists($filePath)) {
            return unlink($filePath);
        }

        return true;
    }

    protected function getFilePath(string $key): string
    {
        return "{$this->savePath}/{$key}";
    }

    protected function ensureDirectory(string $directory): bool
    {
        return is_dir($directory) || mkdir($directory, 0755, true);
    }
}

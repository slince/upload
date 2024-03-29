<?php

namespace Slince\Upload\Filesystem;

use RuntimeException;
use Slince\Upload\File;

class Local implements FilesystemInterface
{
    /**
     * @var string
     */
    protected string $savePath;

    public function __construct(string $savePath)
    {
        $this->savePath = rtrim($savePath, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function upload(File $file, bool $overwrite = false): void
    {
        if (!$this->ensureDirectory($this->savePath)) {
            throw new \InvalidArgumentException(sprintf('Directory "%s" was not created', $this->savePath));
        }

        if (!is_writable($this->savePath)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is invalid', $this->savePath));
        }

        $filePath = $this->getFilePath($file->getName());
        if (!$overwrite && file_exists($filePath)) {
            throw new RuntimeException(sprintf('The file with key "%s" is exists.', $file->getName()));
        }

        $splFile = $file->getUploadedFile()->move(dirname($filePath), basename($filePath));
        $file->setMetadata('spl_file', $splFile);
    }

    /**
     * {@inheritdoc}
     */
    public function delete(File $file): bool
    {
        $splFile = $file->getMetadata('spl_file');

        if (null !== $splFile && file_exists($splFile->getPathname())) {
            return unlink($splFile->getPathname());
        }

        return true;
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

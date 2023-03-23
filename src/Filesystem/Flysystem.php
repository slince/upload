<?php

namespace Slince\Upload\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use RuntimeException;
use Slince\Upload\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Flysystem implements FilesystemInterface
{
    /**
     * @var Filesystem
     */
    protected Filesystem $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function upload(string $key, UploadedFile $file, bool $overwrite = false): void
    {
        try {
            $this->uploadToFlysystem($key, $file);
        } catch (RuntimeException $exception) {
            if (!$overwrite) {
                throw new RuntimeException(sprintf('The file with key "%s" is exists.', $key));
            }
            $this->filesystem->delete($key);
            $this->uploadToFlysystem($key, $file);
        }
        @unlink($file->getPathname()); //remove old
    }

    /**
     * {@inheritdoc}
     */
    public function delete(File $file): bool
    {
        $this->filesystem->delete($file->getName());

        return true;
    }

    /**
     * @param string $key
     * @param UploadedFile $file
     * @throws RuntimeException
     */
    protected function uploadToFlysystem(string $key, UploadedFile $file): void
    {
        try {
            $this->filesystem->writeStream($key, fopen($file->getPathname(), 'r'));
        } catch (FilesystemException $exception) {
            throw new RuntimeException('Failed to upload to flysystem');
        }
    }
}

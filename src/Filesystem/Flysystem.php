<?php

namespace Slince\Upload\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemException;
use RuntimeException;
use Slince\Upload\File;

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
    public function upload(File $file, bool $overwrite = false): void
    {
        try {
            $this->uploadToFlysystem($file);
        } catch (RuntimeException $exception) {
            if (!$overwrite) {
                throw new RuntimeException(sprintf('The file with key "%s" is exists.', $file->getName()));
            }
            $this->filesystem->delete($file->getName());
            $this->uploadToFlysystem($file);
        }
        @unlink($file->getUploadedFile()->getPathname()); //remove old
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
     * @param File $file
     */
    protected function uploadToFlysystem(File $file): void
    {
        try {
            $this->filesystem->writeStream($file->getName(), fopen($file->getUploadedFile()->getPathname(), 'r'));
        } catch (FilesystemException $exception) {
            throw new RuntimeException('Failed to upload to flysystem:' . $exception->getMessage());
        }
    }
}

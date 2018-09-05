<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Filesystem;

use League\Flysystem\FileExistsException;
use League\Flysystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Flysystem implements FilesystemInterface
{
    /**
     * @var Filesystem
     */
    protected $filesystem;

    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * {@inheritdoc}
     */
    public function upload($key, UploadedFile $file, $overwrite = false)
    {
        try {
            $this->uploadToFlysystem($key, $file);
        } catch (FileExistsException $exception) {
            if (!$overwrite) {
                throw new \RuntimeException(sprintf('The file with key "%s" is exists.', $key));
            }
            $this->filesystem->delete($key);
            $this->uploadToFlysystem($key, $file);
        }
        @unlink($file->getPathname()); //remove old
        return true;
    }

    /**
     * @param string $key
     * @param UploadedFile $file
     * @throws FileExistsException
     */
    protected function uploadToFlysystem($key, UploadedFile $file)
    {
        if (!$this->filesystem->writeStream($key, fopen($file->getPathname(), 'r'))) {
            throw new \RuntimeException('Failed to upload to flysystem');
        }
    }
}
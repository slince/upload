<?php

namespace Slince\Upload\Filesystem;

use Slince\Upload\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesystemInterface
{
    /**
     * Uploads the file to filesystem
     *
     * @param string $key
     * @param UploadedFile $file
     * @param boolean $overwrite
     * @return mixed
     */
    public function upload(string $key, UploadedFile $file, bool $overwrite);

    /**
     * Delete file
     *
     * @param File $file
     * @return bool
     */
    public function delete(File $file): bool;
}

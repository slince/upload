<?php

namespace Slince\Upload\Filesystem;

use Slince\Upload\File;

interface FilesystemInterface
{
    /**
     * Uploads the file to filesystem
     *
     * @param File $file
     * @param boolean $overwrite
     * @return void
     */
    public function upload(File $file, bool $overwrite): void;

    /**
     * Delete file
     *
     * @param File $file
     * @return bool
     */
    public function delete(File $file): bool;
}

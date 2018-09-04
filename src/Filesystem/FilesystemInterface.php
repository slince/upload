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

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FilesystemInterface
{
    /**
     * Uploads the file to filesystem
     *
     * @param string $key
     * @param UploadedFile $file
     * @param boolean $overwrite
     * @return File
     */
    public function upload($key, UploadedFile $file, $overwrite);
}
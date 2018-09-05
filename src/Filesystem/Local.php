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

use Symfony\Component\HttpFoundation\File\UploadedFile;

class Local implements FilesystemInterface
{
    /**
     * @var string
     */
    protected $savePath;

    public function __construct($savePath)
    {
        if (!is_dir($savePath)) {
            throw new \InvalidArgumentException(sprintf('The directory "%s" is invalid', $savePath));
        }
        $this->savePath = rtrim($savePath, '\\/');
    }

    /**
     * {@inheritdoc}
     */
    public function upload($key, UploadedFile $file, $overwrite = false)
    {
        $filePath = $this->getFilePath($key);
        if (file_exists($filePath) && !$overwrite) {
            throw new \RuntimeException(sprintf('The file with key "%s" is exists.', $key));
        }
        return $file->move(dirname($filePath), basename($filePath));
    }

    protected function getFilePath($key)
    {
        return "{$this->savePath}/{$key}";
    }
}
<?php

namespace Slince\Upload\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Utils
{
    const DST_DIR = __DIR__ . '/Fixtures/dst';

    const FLY_DIR = __DIR__ . '/Fixtures/flysystem';

    public static function createFile($name, $removeOld = true)
    {
        $filepath = __DIR__ . '/Fixtures/hello.txt';
        $copyFilePath = __DIR__ . '/Fixtures/hello-tmp.txt';
        if ($removeOld) {
            $dstFilePath = static::DST_DIR . '/' .$name;
            @unlink($dstFilePath);
        }
        @copy($filepath, $copyFilePath);

        $reflection = new \ReflectionClass(UploadedFile::class);
        if ($reflection->getConstructor()->getNumberOfParameters() === 5) {
            $file = new UploadedFile(
                $copyFilePath,
                $name,
                'text/plain',
                null,
                true
            );
        } else {
            $file = new UploadedFile(
                $copyFilePath,
                $name,
                'text/plain',
                filesize($copyFilePath),
                UPLOAD_ERR_OK,
                true
            );
        }

        return $file;
    }
}
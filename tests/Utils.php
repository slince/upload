<?php

namespace Slince\Upload\Tests;

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Utils
{
    const DST_DIR = __DIR__ . '/Fixtures/dst';

    public static function createFile($name, $removeOld = true)
    {
        $filepath = __DIR__ . '/Fixtures/hello.txt';
        $copyFilePath = __DIR__ . '/Fixtures/hello-tmp.txt';
        if ($removeOld) {
            $dstFilePath = static::DST_DIR . '/' .$name;
            @unlink($dstFilePath);
        }
        @copy($filepath, $copyFilePath);
        $file = new UploadedFile(
            $copyFilePath,
            $name,
            'text/plain',
            null,
            true
        );
        return $file;
    }
}
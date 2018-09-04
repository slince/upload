<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Temp;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TempTest extends TestCase
{
    public function testUpload()
    {
        $filepath = __DIR__ . '/../Fixtures/hello.txt';
        $copyFilePath = __DIR__ . '/../Fixtures/hello-tmp.txt';
        copy($filepath, $copyFilePath);
        $file = new UploadedFile(
            $copyFilePath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );

        $temp = new Temp();
        $temp->upload('hello-3.txt', $file);
    }
}
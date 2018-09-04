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
        $dstFilePath = sys_get_temp_dir() . '/hello-3.txt';
        @unlink($dstFilePath);
        copy($filepath, $copyFilePath);
        $file = new UploadedFile(
            $copyFilePath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );

        $temp = new Temp();
        $file = $temp->upload('hello-3.txt', $file);
        $this->assertContains(sys_get_temp_dir(), $file->getPathname());
    }
}
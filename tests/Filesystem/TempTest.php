<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Temp;
use Slince\Upload\Tests\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class TempTest extends TestCase
{
    public function testUpload()
    {
        $dstFilePath = sys_get_temp_dir() . '/hello-3.txt';
        @unlink($dstFilePath);
        $file = Utils::createFile('hello2.txt');
        $temp = new Temp();
        $file = $temp->upload('hello-3.txt', $file);
        $this->assertContains(sys_get_temp_dir(), $file->getPathname());
    }
}
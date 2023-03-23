<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\File;
use Slince\Upload\Filesystem\Temp;
use Slince\Upload\Tests\Utils;

class TempTest extends TestCase
{
    public function testUpload()
    {
        $dstFilePath = sys_get_temp_dir() . '/hello-3.txt';
        @unlink($dstFilePath);
        $uploadedFile = Utils::createFile('hello2.txt');
        $temp = new Temp();
        $file = new File('hello-3.txt', $uploadedFile);
        $temp->upload($file);
        $this->assertStringContainsStringIgnoringCase(sys_get_temp_dir(), $file->getMetadata('spl_file')->getPathname());
    }
}
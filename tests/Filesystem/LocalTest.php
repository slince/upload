<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Tests\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalTest extends TestCase
{
    public function testUpload()
    {
        $file = Utils::createFile('hello2.txt');
        $local = new Local(Utils::DST_DIR);
        $local->upload('hello2.txt', $file);

        $this->assertFileExists(Utils::DST_DIR . '/hello2.txt');
    }
}
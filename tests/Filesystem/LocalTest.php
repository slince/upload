<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\File;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Tests\Utils;

class LocalTest extends TestCase
{
    public function testUpload()
    {
        $file = Utils::createFile('hello2.txt');
        $local = new Local(Utils::DST_DIR);
        $local->upload(new File('hello2.txt', $file));

        $this->assertFileExists(Utils::DST_DIR . '/hello2.txt');
    }
}
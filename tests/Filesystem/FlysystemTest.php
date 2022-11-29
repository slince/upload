<?php

namespace Slince\Upload\Tests\Filesystem;

use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Flysystem;
use Slince\Upload\Tests\Utils;

class FlysystemTest extends TestCase
{
    public function testUpload()
    {
        if (!class_exists('League\Flysystem\Filesystem')) {
            $this->markTestSkipped();
        }
        $file = Utils::createFile('hello-3.txt');

        $localAdapter = new LocalFilesystemAdapter(Utils::FLY_DIR);
        $flysystem = new Flysystem(new Filesystem($localAdapter));

        $result = $flysystem->upload('hello-4.txt', $file, true);
        $this->assertTrue($result);
    }
}
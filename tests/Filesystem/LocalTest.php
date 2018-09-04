<?php

namespace Slince\Upload\Tests\Filesystem;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Local;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class LocalTest extends TestCase
{
    public function testUpload()
    {
        $filepath = __DIR__ . '/../Fixtures/hello.txt';
        $file = new UploadedFile(
            $filepath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );
        $local = new Local(__DIR__ . '/../Fixtures/dst/');
        $local->upload('hello-2.txt', $file);
    }

    public function testException()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Local(__DIR__ . '/../Fixtures/not-exist/');
    }
}
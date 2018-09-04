<?php

namespace Slince\Upload\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Naming\GenericNamer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenericNamerTest extends TestCase
{
    public function testGenerate()
    {
        $filepath = __DIR__ . '/../Fixtures/hello.txt';
        $file = new UploadedFile(
            $filepath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );
        $namer = new GenericNamer();
        $this->assertEquals('hello2.txt', $namer->generate($file));
    }
}
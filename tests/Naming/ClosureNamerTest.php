<?php

namespace Slince\Upload\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Naming\ClosureNamer;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClosureNamerTest extends TestCase
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
        $namer = new ClosureNamer(function(UploadedFile $file){
            return 'foo';
        });
        $this->assertEquals('foo', $namer->generate($file));
    }
}
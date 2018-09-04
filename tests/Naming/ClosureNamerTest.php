<?php

namespace Slince\Upload\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Naming\ClosureNamer;
use Slince\Upload\Tests\Utils;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClosureNamerTest extends TestCase
{
    public function testGenerate()
    {
        $file = Utils::createFile('hello2.txt');
        $namer = new ClosureNamer(function(UploadedFile $file){
            return 'foo';
        });
        $this->assertEquals('foo', $namer->generate($file));
    }
}
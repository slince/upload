<?php

namespace Slince\Upload\Tests\Naming;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\Tests\Utils;

class GenericNamerTest extends TestCase
{
    public function testGenerate()
    {
        $file = Utils::createFile('hello2.txt');

        $namer = new GenericNamer();
        $this->assertEquals('hello2.txt', $namer->generate($file));
    }
}
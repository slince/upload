<?php

namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\UploadHandler;
use Slince\Upload\Validator;

class UploaderHandlerTest extends TestCase
{
    public function testHandle()
    {
        $handler = new UploadHandler(new Local(__DIR__ . '/Fixtures/dst'), new GenericNamer());
        $this->assertInfinite(Validator::class, $handler->getValidator());



        $handler->handle();
    }
}
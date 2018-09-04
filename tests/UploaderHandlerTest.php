<?php

namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\UploadHandler;
use Slince\Upload\Validator;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploaderHandlerTest extends TestCase
{
    public function testHandle()
    {
        $file = Utils::createFile('hello-test-handle');
        $handler = $this->mockHandler([$file]);
        // test validator
        $this->assertInstanceOf(Validator::class, $handler->getValidator());

        //test handle
        $file = $handler->handle();
        $this->assertInstanceOf(File::class, $file);
        $this->assertContains('dst', $file->getPathname());
    }

    protected function handleMulti()
    {
        $file = Utils::createFile('hello-test-handle-multi');
        $file2 = Utils::createFile('hello-test-handle-multi2');

        $handler = $this->mockHandler([$file, $file2]);
        $files = $handler->handle();
        $this->assertCount(2, $files);
    }

    public function testOverwrite()
    {
        $file = Utils::createFile('hello-test-overwrite');
        $handler = $this->mockHandler([$file]);
        $handler->handle();

        //upload again
        $file = Utils::createFile('hello-test-overwrite', false);
        $handler = $this->mockHandler([$file]);
        $result = $handler->handle();
        $this->assertInstanceOf(\RuntimeException::class, $result);

        //start overwrite mode.
        $handler = $this->mockHandler([$file], true);
        $result = $handler->handle();
        $this->assertInstanceOf(File::class, $result);
    }


    /**
     * @param UploadedFile[] $files
     * @param boolean $overwrite
     * @return UploadHandler
     */
    protected function mockHandler($files, $overwrite = false)
    {
        $filesystem = new Local(__DIR__ . '/Fixtures/dst');
        $namer = new GenericNamer();
        //mock handler
        $handler = $this->getMockBuilder(UploadHandler::class)
            ->setConstructorArgs([$filesystem, $namer, $overwrite])
            ->setMethods(['createFiles'])
            ->getMock();
        $handler->method('createFiles')
            ->willReturn($files);

        return $handler;
    }
}
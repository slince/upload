<?php

namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\File;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\UploadHandler;
use Slince\Upload\Validator;
use Symfony\Component\HttpFoundation\FileBag;

class UploadHandlerTest extends TestCase
{
    public function testHandle()
    {
        $file = Utils::createFile('hello-test-handle');
        $handler = $this->mockHandler(new FileBag([$file]));
        // test validator
        $this->assertInstanceOf(Validator::class, $handler->getValidator());

        //test handle
        $files = $handler->handle();
        $this->assertInstanceOf(File::class, $files[0]);
        $this->assertStringContainsStringIgnoringCase('dst', $files[0]->getDetails()->getPathname());
    }

    protected function handleMulti()
    {
        $file = Utils::createFile('hello-test-handle-multi');
        $file2 = Utils::createFile('hello-test-handle-multi2');

        $handler = $this->mockHandler(new FileBag([$file, $file2]));
        $files = $handler->handle();
        $this->assertCount(2, $files);
    }

    public function testOverwrite()
    {
        $file = Utils::createFile('hello-test-overwrite');
        $handler = $this->mockHandler(new FileBag([$file]));
        $handler->handle();

        //upload again
        $file = Utils::createFile('hello-test-overwrite', false);
        $handler = $this->mockHandler(new FileBag([$file]));
        $result = $handler->handle();

        $this->assertInstanceOf(\RuntimeException::class, $result[0]->getException());

        //start overwrite mode.
        $handler = $this->mockHandler(new FileBag([$file]), true);
        $result = $handler->handle();
        $this->assertInstanceOf(File::class, $result[0]);
    }


    /**
     * @param FileBag $files
     * @param boolean $overwrite
     * @return UploadHandler
     */
    protected function mockHandler(FileBag $files, bool $overwrite = false)
    {
        $filesystem = new Local(__DIR__ . '/Fixtures/dst');
        $namer = new GenericNamer();
        //mock handler
        $handler = $this->getMockBuilder(UploadHandler::class)
            ->setConstructorArgs([$filesystem, $namer, null, null, $overwrite])
            ->setMethods(['createUploadedFiles'])
            ->getMock();
        $handler->method('createUploadedFiles')
            ->willReturn($files);

        return $handler;
    }
}
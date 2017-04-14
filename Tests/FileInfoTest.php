<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Exception\UploadException;
use Slince\Upload\FileInfo;

class FileInfoTest extends TestCase
{
    /**
     * Create instance
     * @return FileInfo
     */
    protected function createInstance()
    {
        return new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'error' => 0,
            'size' => 105190,
        ]);
    }

    public function testGetBaseInfo()
    {
        $fileInfo = $this->createInstance();
        $this->assertEquals('thumb.zip', $fileInfo->getOriginName());
        $this->assertEquals('application/x-zip-compressed', $fileInfo->getType());
        $this->assertEquals(__DIR__ . '/Fixtures/foo.zip', $fileInfo->getTmpName());
        $this->assertEquals(0, $fileInfo->getError());
        $this->assertEquals(105190, $fileInfo->getSize());
    }

    public function testFromArray()
    {
        $fileInfo = FileInfo::fromArray([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'error' => 0,
            'size' => 105190,
        ]);
        $this->assertInstanceOf(FileInfo::class, $fileInfo);
        $this->assertEquals('thumb.zip', $fileInfo->getOriginName());
        $this->assertEquals('application/x-zip-compressed', $fileInfo->getType());
        $this->assertEquals(__DIR__ . '/Fixtures/foo.zip', $fileInfo->getTmpName());
        $this->assertEquals(0, $fileInfo->getError());
        $this->assertEquals(105190, $fileInfo->getSize());
    }

    public function testSetErrorCode()
    {
        $fileInfo = $this->createInstance();
        $this->assertNull($fileInfo->getErrorCode());
        $fileInfo->setErrorCode(123);
        $this->assertEquals(123, $fileInfo->getErrorCode());
    }

    public function testSetErrorMessage()
    {
        $fileInfo = $this->createInstance();
        $this->assertNull($fileInfo->getErrorMsg());
        $fileInfo->setErrorMsg('foo message');
        $this->assertEquals('foo message', $fileInfo->getErrorMsg());
    }

    public function testSetPath()
    {
        $fileInfo = $this->createInstance();
        $this->assertNull($fileInfo->getPath());
        $fileInfo->setPath('/path/to/file');
        $this->assertEquals('/path/to/file', $fileInfo->getPath());
    }

    public function testSetHasError()
    {
        $fileInfo = $this->createInstance();
        $this->assertNull($fileInfo->hasError());
        $fileInfo->setHasError(false);
        $this->assertFalse($fileInfo->hasError());
    }

    public function testGetMimeType()
    {
        $fileInfo = $this->createInstance();
        $mimeType = $fileInfo->getMimeType();
        $this->assertEquals('application/zip', $mimeType);
    }

    public function testInvalidArrayException()
    {
        $this->setExpectedException(UploadException::class);
        new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'error' => 0,
            'size' => 105190,
        ]);
    }
}

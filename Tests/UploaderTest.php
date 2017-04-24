<?php
namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\SizeRule;
use Slince\Upload\Uploader;

class UploaderTest extends TestCase
{
    public function testGetter()
    {
        $uploader = new Uploader();
        $this->assertFalse($uploader->getOverride());
        $this->assertFalse($uploader->getIsRandName());
        $this->assertFalse($uploader->isRandName());

        $uploader->setSavePath('./dst');
        $uploader->setOverride(true);
        $uploader->setRandName(true);
        $uploader->setIsRandName(true);
        $this->assertEquals('./dst' . DIRECTORY_SEPARATOR, $uploader->getSavePath());
        $this->assertTrue($uploader->getOverride());
        $this->assertTrue($uploader->getIsRandName());
        $this->assertTrue($uploader->isRandName());
    }

    public function testGetFilenameGenerator()
    {
        $uploader = new Uploader();
        $callback = function (FileInfo $fileInfo) {
            return '/dst/' . $fileInfo->getOriginName();
        };

        $this->assertFalse($callback === $uploader->getFilenameGenerator());
        $uploader->setFilenameGenerator($callback);
        $this->assertEquals($callback, $uploader->getFilenameGenerator());
    }

    public function testAddRule()
    {
        $uploader = new Uploader();
        $this->assertCount(1, $uploader->getRules());
        $uploader->addRule(new SizeRule(1000, 2000));
        $this->assertCount(2, $uploader->getRules());
    }

    public function testGenerateRandFilename()
    {
        $uploader = $this->createUploaderMock();
        $uploader->setSavePath(__DIR__ . '/Fixtures/dst');
        $uploader->setRandName(true);
        $uploader->setIsRandName(true);
        $fileArray = [
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'error' => 0,
            'size' => 105190,
        ];
        $fileInfo = $uploader->process($fileArray);
        $this->assertFalse($fileInfo->hasError());
        $this->assertNotEmpty($fileInfo->getPath());
        $this->assertNotContains($fileInfo->getOriginName(), $fileInfo->getPath());
    }

    public function testGenerateOriginFilename()
    {
        $uploader = $this->createUploaderMock();
        $uploader->setSavePath(__DIR__ . '/Fixtures/dst');
        $fileArray = [
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'error' => 0,
            'size' => 105190,
        ];
        $fileInfo = $uploader->process($fileArray);
        $this->assertFalse($fileInfo->hasError());
        $this->assertContains($fileInfo->getOriginName(), $fileInfo->getPath());
    }

    public function testGenerateCustomFilename()
    {
        $uploader = $this->createUploaderMock();
        $uploader->setSavePath(__DIR__ . '/Fixtures/dst');

        $fileArray = [
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'error' => 0,
            'size' => 105190,
        ];

        $uploader->setFilenameGenerator(function (FileInfo $fileInfo) {
            return 'foo-bar.ext';
        });

        $fileInfo = $uploader->process($fileArray);
        $this->assertFalse($fileInfo->hasError());
        $this->assertContains('foo-bar.ext', $fileInfo->getPath());
    }

    /**
     * @return Uploader
     */
    protected function createUploaderMock()
    {
        $mock =  $this->getMockBuilder(Uploader::class)
            ->setMethods(['moveUploadedFile'])
            ->getMock();
        $mock->expects($this->any())
            ->method('moveUploadedFile')
            ->will($this->returnValue(true));
        return $mock;
    }
}

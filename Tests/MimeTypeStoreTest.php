<?php
namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\MimeTypeStore;

class MimeTypeStoreTest extends TestCase
{
    public function testAll()
    {
        $this->assertNotEmpty(MimeTypeStore::getAll());
    }

    public function testExtensionExists()
    {
        $this->assertTrue(MimeTypeStore::extensionExist('jpg'));
        $this->assertFalse(MimeTypeStore::extensionExist('not-exists-ext'));
    }

    public function testGetMimeTypes()
    {
        $this->assertEquals('image/jpeg', MimeTypeStore::getMimeType('jpg'));
        $this->assertTrue('array' == gettype(MimeTypeStore::getMimeType('xhtml')));
    }
}
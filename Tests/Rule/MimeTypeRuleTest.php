<?php
namespace Slince\Upload\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\MimeTypeRule;

class MimeTypeRuleTest extends TestCase
{
    public function testValidate()
    {
        $rule = new MimeTypeRule([
            'text/plain'
        ]);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/../Fixtures/foo.zip',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertFalse($result);
        $this->assertGreaterThan(0, $rule->getErrorCode());
        $this->assertNotEmpty($rule->getErrorMsg());
    }

    public function testWildcard()
    {
        $rule = new MimeTypeRule([
            'image/*'
        ]);
        //validate jpeg
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'images/jpeg',
            'tmp_name' => __DIR__ . '/../Fixtures/image-jpeg.jpg',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);

        //validate png
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'images/jpeg',
            'tmp_name' => __DIR__ . '/../Fixtures/image-png.png',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);

        //validate zip
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/../Fixtures/foo.zip',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertFalse($result);
    }
}

<?php
namespace Slince\Upload\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\ExtensionRule;
use Slince\Upload\Rule\SizeRule;

class ExtensionRuleTest extends TestCase
{
    public function testValidate()
    {
        $rule = new ExtensionRule([
            'jpg', 'png'
        ]);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => 2001,
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertFalse($result);
        $this->assertGreaterThan(0, $rule->getErrorCode());
        $this->assertNotEmpty($rule->getErrorMsg());

        $fileInfo = new FileInfo([
            'name' => 'foo.jpg',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.jpg',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);
    }
}
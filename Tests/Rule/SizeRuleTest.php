<?php
namespace Slince\Upload\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\SizeRule;

class SizeRuleTest extends TestCase
{
    public function testValidate()
    {
        $rule = new SizeRule(1000, 2000);
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
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => 999
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertFalse($result);
        $this->assertGreaterThan(0, $rule->getErrorCode());
        $this->assertNotEmpty($rule->getErrorMsg());
    }

    public function testBoundary()
    {
        $rule = new SizeRule(1000, 2000);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => 1000,
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => 2000,
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);
    }

    public function testPartial()
    {
        $rule = new SizeRule(1000, null);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => PHP_INT_MAX,
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);


        $rule = new SizeRule(null, 2000);
        $fileInfo = new FileInfo([
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' =>'/path/to/foo.zip',
            'error' => 0,
            'size' => PHP_INT_MIN,
        ]);
        $result = $rule->validate($fileInfo);
        $this->assertTrue($result);
    }
}
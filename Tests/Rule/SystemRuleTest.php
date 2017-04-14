<?php
namespace Slince\Upload\Tests\Rule;

use PHPUnit\Framework\TestCase;
use Slince\Upload\ErrorStore;
use Slince\Upload\FileInfo;
use Slince\Upload\Rule\SystemRule;

class SystemRuleTest extends TestCase
{
    public function testValidate()
    {
        $rule = new SystemRule();
        $result = $rule->validate(new FileInfo([
            'error' => ErrorStore::ERROR_NO_FILE,
            'name' => 'thumb.zip',
            'type' => 'application/x-zip-compressed',
            'tmp_name' => __DIR__ . '/Fixtures/foo.zip',
            'size' => 105190,
        ]));
        $this->assertFalse($result);
        $this->assertEquals(ErrorStore::ERROR_NO_FILE, $rule->getErrorCode());
        $this->assertNotEmpty($rule->getErrorMsg());
    }
}
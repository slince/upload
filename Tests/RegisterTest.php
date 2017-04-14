<?php
namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Registry;

class RegisterTest extends TestCase
{
    public function testPolyfill()
    {
        $this->assertTrue(class_exists(Registry::class));
    }
}

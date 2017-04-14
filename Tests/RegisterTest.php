<?php
namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Registry;

class RegisterTest extends TestCase
{
    function testPolyfill()
    {
        $this->assertTrue(class_exists(Registry::class));
    }
}
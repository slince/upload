<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Constraint\SizeConstraint;
use Slince\Upload\Tests\Utils;

class SizeConstraintTest extends TestCase
{
    public function testValid()
    {
        $filepath = __DIR__ . '/../Fixtures/hello.txt';
        $file = Utils::createFile('hello2.txt');

        $constraint = new SizeConstraint(filesize($filepath) - 100, 2000);
        $this->assertTrue($constraint->validate($file));

        $constraint = new SizeConstraint(filesize($filepath) + 100, 2000);
        $this->assertFalse($constraint->validate($file));
        $this->assertStringContainsStringIgnoringCase('between', $constraint->getErrorMessage($file));
    }
}
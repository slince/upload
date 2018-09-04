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
use Slince\Upload\Constraint\ExtensionConstraint;
use Slince\Upload\Tests\Utils;

class ExtensionConstraintTest extends TestCase
{
    public function testValid()
    {
        $file = Utils::createFile('hello2.txt');

        $constraint = new ExtensionConstraint(['txt']);
        $this->assertTrue($constraint->validate($file));
        $this->assertContains('File extension', $constraint->getErrorMessage($file));

        $constraint = new ExtensionConstraint(['jpg']);
        $this->assertFalse($constraint->validate($file));
    }
}
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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtensionConstraintTest extends TestCase
{
    public function testValid()
    {
        $filepath = __DIR__ . '/../Fixtures/hello.txt';
        $file = new UploadedFile(
            $filepath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );

        $constraint = new ExtensionConstraint(['txt']);
        $this->assertTrue($constraint->validate($file));
        $this->assertContains('File extension', $constraint->getErrorMessage($file));

        $constraint = new ExtensionConstraint(['jpg']);
        $this->assertFalse($constraint->validate($file));
    }
}
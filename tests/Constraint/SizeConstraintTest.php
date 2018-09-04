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
use Symfony\Component\HttpFoundation\File\UploadedFile;

class SizeConstraintTest extends TestCase
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
        $constraint = new SizeConstraint(filesize($filepath) - 100, 2000);
        $this->assertTrue($constraint->validate($file));

        $constraint = new SizeConstraint(filesize($filepath) + 100, 2000);
        $this->assertFalse($constraint->validate($file));
        $this->assertContains('between', $constraint->getErrorMessage($file));


        $constraint = new SizeConstraint(filesize($filepath) + 100);
        $this->assertFalse($constraint->validate($file));
        $this->assertContains('greater than', $constraint->getErrorMessage($file));

        $constraint = new SizeConstraint(null,filesize($filepath) - 2);
        $this->assertFalse($constraint->validate($file));
        $this->assertContains('less than', $constraint->getErrorMessage($file));
    }
}
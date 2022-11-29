<?php

namespace Slince\Upload\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Constraint\MimeTypeConstraint;
use Slince\Upload\Tests\Utils;

class MimeTypeConstraintTest extends TestCase
{
    public function testValid()
    {
        $file = Utils::createFile('hello2.txt');

        $constraint = new MimeTypeConstraint(['text/plain']);
        $this->assertTrue($constraint->validate($file));
        $this->assertStringContainsStringIgnoringCase('File type', $constraint->getErrorMessage($file));

        $constraint = new MimeTypeConstraint(['image/jpeg']);
        $this->assertFalse($constraint->validate($file));

        $constraint = new MimeTypeConstraint(['text/*']);
        $this->assertTrue($constraint->validate($file));
    }
}
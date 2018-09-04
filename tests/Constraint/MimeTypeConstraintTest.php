<?php

namespace Slince\Upload\Tests\Constraint;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Constraint\MimeTypeConstraint;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MimeTypeConstraintTest extends TestCase
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
        $constraint = new MimeTypeConstraint(['text/plain']);
        $this->assertTrue($constraint->validate($file));
        $this->assertContains('File type', $constraint->getErrorMessage($file));

        $constraint = new MimeTypeConstraint(['image/jpeg']);
        $this->assertFalse($constraint->validate($file));

        $constraint = new MimeTypeConstraint(['text/*']);
        $this->assertTrue($constraint->validate($file));
    }
}
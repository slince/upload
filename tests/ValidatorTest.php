<?php

namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Constraint\MimeTypeConstraint;
use Slince\Upload\Exception\ConstraintException;
use Slince\Upload\Validator;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ValidatorTest extends TestCase
{
    public function testValidate()
    {
        $constraint = new MimeTypeConstraint(['text/plain']);
        $validator = new Validator();
        $validator->addConstraint($constraint);

        $filepath = __DIR__ . '/Fixtures/hello.txt';
        $file = new UploadedFile(
            $filepath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );
        $this->assertTrue($validator->validate($file));


    }

    public function testException()
    {
        //exception
        $this->expectException(ConstraintException::class);
        $constraint = new MimeTypeConstraint(['image/jpeg']);
        $validator = new Validator();
        $validator->addConstraint($constraint);

        $filepath = __DIR__ . '/Fixtures/hello.txt';
        $file = new UploadedFile(
            $filepath,
            'hello2.txt',
            'text/plain',
            null,
            true
        );
        $this->assertTrue($validator->validate($file));
    }
}
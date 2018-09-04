<?php

namespace Slince\Upload\Tests;

use PHPUnit\Framework\TestCase;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\ClosureNamer;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\UploadHandler;
use Slince\Upload\UploadHandlerBuilder;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadHandlerBuilderTest extends TestCase
{
    public function testSimpleBuild()
    {
        $builder = new UploadHandlerBuilder();

        $handler = $builder->saveTo(Utils::DST_DIR)->getHandler();
        $this->assertInstanceOf(UploadHandler::class, $handler);

        $this->assertAttributeInstanceOf(GenericNamer::class, 'namer', $handler);
        $this->assertAttributeInstanceOf(Local::class, 'filesystem', $handler);
    }

    public function testAdvanceBuild()
    {
        $builder = new UploadHandlerBuilder();
        $handler = $builder
            //Custom namer
            ->naming(function (UploadedFile $file) {
                return date('Y/md') . '/' . uniqid() . '.' . $file->getClientOriginalExtension();
            })

            //add constraints
            ->sizeBetween('10m', '20m')
            ->allowExtensions(['jpg', 'txt'])
            ->allowMimeTypes(['image/*', 'text/plain'])

            ->saveTo(Utils::DST_DIR)
            ->getHandler();

        $this->assertAttributeInstanceOf(ClosureNamer::class, 'namer', $handler);
        $this->assertAttributeInstanceOf(Local::class, 'filesystem', $handler);
    }

    public function testErrorBuild()
    {
        $builder = new UploadHandlerBuilder();
        $this->expectException(\LogicException::class, 'You should set a filesystem for the builder');
        $builder->getHandler();
    }
}
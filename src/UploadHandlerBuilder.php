<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload;


use Slince\Upload\Constraint\ConstraintInterface;
use Slince\Upload\Constraint\ExtensionConstraint;
use Slince\Upload\Constraint\MimeTypeConstraint;
use Slince\Upload\Constraint\SizeConstraint;
use Slince\Upload\Filesystem\FilesystemInterface;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\Naming\NamerInterface;
use Slince\Upload\Naming\ClosureNamer;

final class UploadHandlerBuilder
{
    /**
     * @var boolean
     */
    protected $overwrite;

    /**
     * @var NamerInterface
     */
    protected $namer;

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var ConstraintInterface[]
     */
    protected $constraints = [];

    /**
     * Sets overwrite mode.
     *
     * @param boolean $overwrite
     * @return $this
     */
    public function overwrite($overwrite = true)
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * Set allowed mime types.
     *
     * @param array|string $mimeTypes
     * @return $this
     */
    public function allowMimeTypes($mimeTypes)
    {
        if (!is_array($mimeTypes)) {
            $mimeTypes = [$mimeTypes];
        }
        $this->constraints[] = new MimeTypeConstraint($mimeTypes);
        return $this;
    }

    /**
     * Set size range
     *
     * @param string|int|null $from
     * @param string|int|null $to
     * @return $this
     */
    public function sizeBetween($from, $to)
    {
        $this->constraints[] = new SizeConstraint($from, $to);
        return $this;
    }

    /**
     * Set allowed extensions
     *
     * @param array|string $extensions
     * @return $this
     */
    public function allowExtensions($extensions)
    {
        if (!is_array($extensions)) {
            $extensions = [$extensions];
        }
        $this->constraints[] = new ExtensionConstraint($extensions);
        return $this;
    }

    /**
     * Sets namer.
     *
     * @param \Closure|NamerInterface $namer
     * @return $this
     */
    public function naming($namer)
    {
        if ($namer instanceof \Closure) {
            $namer = new ClosureNamer($namer);
        }
        $this->namer = $namer;
        return $this;
    }

    /**
     * Set filesystem.
     *
     * @param FilesystemInterface $filesystem
     * @return $this
     */
    public function setFilesystem(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
        return $this;
    }

    /**
     * Sets save path
     *
     * @param string $path
     * @return $this
     */
    public function saveTo($path)
    {
        return $this->setFilesystem(new Local($path));
    }

    /**
     * Add a constraint
     *
     * @param ConstraintInterface $constraint
     * @return $this
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Make upload handler
     *
     * @return UploadHandler
     */
    public function getHandler()
    {
        if ($this->namer === null) {
            $this->namer = new GenericNamer();
        }
        $handler = new UploadHandler($this->filesystem, $this->namer, $this->overwrite);
        $validator = $handler->getValidator();
        foreach ($this->constraints as $constraint) {
            $validator->addConstraint($constraint);
        }
        return $handler;
    }
}
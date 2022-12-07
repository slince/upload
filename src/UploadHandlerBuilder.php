<?php

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
use Slince\Upload\Processing\ProcessorInterface;

class UploadHandlerBuilder
{
    /**
     * @var boolean
     */
    protected $overwrite = false;

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
     * @var ProcessorInterface[]
     */
    protected $processList = [];

    /**
     * Sets overwrite mode.
     *
     * @param boolean $overwrite
     * @return $this
     */
    public function overwrite(bool $overwrite = true): self
    {
        $this->overwrite = $overwrite;
        return $this;
    }

    /**
     * Set allowed mime types.
     *
     * @param array|string $mimeTypes
     * @param string|null $errorMessageTemplate
     * @return $this
     */
    public function allowMimeTypes($mimeTypes, ?string $errorMessageTemplate = null): self
    {
        if (!is_array($mimeTypes)) {
            $mimeTypes = [$mimeTypes];
        }

        $constraint = new MimeTypeConstraint($mimeTypes);
        if ($errorMessageTemplate) {
            $constraint->setErrorMessage($errorMessageTemplate);
        }

        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Set size range
     *
     * @param string|int|null $from
     * @param string|int|null $to
     * @param string|null $errorMessageTemplate
     * @return $this
     */
    public function sizeBetween($from, $to, ?string $errorMessageTemplate = null): self
    {
        $constraint = new SizeConstraint($from, $to);
        if ($errorMessageTemplate) {
            $constraint->setErrorMessage($errorMessageTemplate);
        }

        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Set allowed extensions
     *
     * @param array|string $extensions
     * @param string|null $errorMessageTemplate
     * @return $this
     */
    public function allowExtensions($extensions, ?string $errorMessageTemplate = null): self
    {
        if (!is_array($extensions)) {
            $extensions = [$extensions];
        }

        $constraint = new ExtensionConstraint($extensions);
        if ($errorMessageTemplate) {
            $constraint->setErrorMessage($errorMessageTemplate);
        }

        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Sets namer.
     *
     * @param \Closure|NamerInterface $namer
     * @return $this
     */
    public function naming($namer): self
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
    public function setFilesystem(FilesystemInterface $filesystem): self
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
    public function saveTo($path): self
    {
        return $this->setFilesystem(new Local($path));
    }

    /**
     * Add a constraint
     *
     * @param ConstraintInterface $constraint
     * @return $this
     */
    public function addConstraint(ConstraintInterface $constraint): self
    {
        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Add to process list
     *
     * @param \Closure|ProcessorInterface $process
     * @return $this
     */
    public function addProcess($process): self
    {
        $this->processList[] = $process;
        return $this;
    }

    /**
     * Make upload handler
     *
     * @return UploadHandler
     */
    public function getHandler(): UploadHandler
    {
        if ($this->namer === null) {
            $this->namer = new GenericNamer();
        }

        if ($this->filesystem === null) {
            throw new \LogicException(sprintf('You should set a filesystem for the builder.'));
        }

        $handler = new UploadHandler($this->filesystem, $this->namer, $this->overwrite);

        $validator = $handler->getValidator();
        foreach ($this->constraints as $constraint) {
            $validator->addConstraint($constraint);
        }

        $processor = $handler->getProcessor();
        foreach ($this->processList as $process) {
            $processor->addProcess($process);
        }

        return $handler;
    }
}

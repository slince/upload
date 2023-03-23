<?php

namespace Slince\Upload;

use Closure;
use LogicException;
use Slince\Upload\Constraint\ConstraintInterface;
use Slince\Upload\Constraint\ExtensionConstraint;
use Slince\Upload\Constraint\MimeTypeConstraint;
use Slince\Upload\Constraint\SizeConstraint;
use Slince\Upload\Filesystem\FilesystemInterface;
use Slince\Upload\Filesystem\Local;
use Slince\Upload\Naming\ClosureNamer;
use Slince\Upload\Naming\GenericNamer;
use Slince\Upload\Naming\NamerInterface;
use Slince\Upload\Processor\ChainProcessor;
use Slince\Upload\Processor\ClosureProcessor;
use Slince\Upload\Processor\ProcessorInterface;

class UploadHandlerBuilder
{
    /**
     * @var boolean
     */
    protected bool $overwrite = false;

    /**
     * @var NamerInterface|null
     */
    protected ?NamerInterface $namer = null;

    /**
     * @var FilesystemInterface|null
     */
    protected ?FilesystemInterface $filesystem = null;

    /**
     * @var ConstraintInterface[]
     */
    protected array $constraints = [];

    /**
     * @var ProcessorInterface[]
     */
    protected array $processors = [];

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
    public function allowMimeTypes(array|string $mimeTypes, ?string $errorMessageTemplate = null): self
    {
        if (!is_array($mimeTypes)) {
            $mimeTypes = [$mimeTypes];
        }

        $constraint = new MimeTypeConstraint($mimeTypes);
        if (null !== $errorMessageTemplate) {
            $constraint->setErrorMessage($errorMessageTemplate);
        }

        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Set size range
     *
     * @param int|string|null $from
     * @param int|string|null $to
     * @param string|null $errorMessageTemplate
     * @return $this
     */
    public function sizeBetween(int|string|null $from, int|string|null $to, ?string $errorMessageTemplate = null): self
    {
        $constraint = new SizeConstraint($from, $to);
        if (null !== $errorMessageTemplate) {
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
    public function allowExtensions(array|string $extensions, ?string $errorMessageTemplate = null): self
    {
        if (!is_array($extensions)) {
            $extensions = [$extensions];
        }

        $constraint = new ExtensionConstraint($extensions);
        if (null !== $errorMessageTemplate) {
            $constraint->setErrorMessage($errorMessageTemplate);
        }

        $this->constraints[] = $constraint;
        return $this;
    }

    /**
     * Sets namer.
     *
     * @param Closure|NamerInterface $namer
     * @return $this
     */
    public function naming(NamerInterface|Closure $namer): self
    {
        if ($namer instanceof Closure) {
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
    public function saveTo(string $path): self
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
     * Add to processor list
     *
     * @param Closure|ProcessorInterface $processor
     * @return $this
     */
    public function addProcessor(ProcessorInterface|Closure $processor): self
    {
        if ($processor instanceof Closure) {
            $processor = new ClosureProcessor($processor);
        }
        $this->processors[] = $processor;
        return $this;
    }

    /**
     * Make upload handler
     *
     * @return UploadHandler
     */
    public function getHandler(): UploadHandler
    {
        if (null === $this->namer) {
            $this->namer = new GenericNamer();
        }

        if (null === $this->filesystem) {
            throw new LogicException('You should set a filesystem for the builder.');
        }

        return new UploadHandler($this->filesystem, $this->namer,
            new Validator($this->constraints),
            new ChainProcessor($this->processors),
            $this->overwrite
        );
    }
}

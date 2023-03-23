<?php

namespace Slince\Upload;

use Exception;
use Slince\Upload\Filesystem\FilesystemInterface;
use Slince\Upload\Naming\NamerInterface;
use Slince\Upload\Processor\ChainProcessor;
use Slince\Upload\Processor\ProcessorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class UploadHandler
{
    /**
     * @var NamerInterface
     */
    protected NamerInterface $namer;

    /**
     * @var FilesystemInterface
     */
    protected FilesystemInterface $filesystem;

    /**
     * @var Validator
     */
    protected Validator $validator;

    /**
     * @var ProcessorInterface
     */
    protected ProcessorInterface $processor;

    /**
     * @var boolean
     */
    protected bool $overwrite;

    public function __construct(
        FilesystemInterface $filesystem,
        NamerInterface $namer,
        Validator $validator = null,
        ProcessorInterface $processor = null,
        bool $overwrite = false
    ) {
        $this->filesystem = $filesystem;
        $this->namer = $namer;
        $this->validator = $validator ?: new Validator();
        $this->processor = $processor ?: new ChainProcessor();
        $this->overwrite = $overwrite;
    }

    /**
     * Gets the validator.
     *
     * @return Validator
     */
    public function getValidator(): Validator
    {
        return $this->validator;
    }

    /**
     * Process request
     * @param SymfonyRequest|null $request
     *
     * @return FileSet
     */
    public function handle(?SymfonyRequest $request = null): FileSet
    {
        $uploadedFiles = $this->createUploadedFiles($request);
        $files =  $this->handleUploadedFiles($uploadedFiles);
        return new FileSet($files, $uploadedFiles);
    }

    /**
     * Clear files from filesystem.
     *
     * @param FileSet $files
     * @return bool
     */
    public function clear(FileSet $files): bool
    {
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->clear(new FileSet($file));
            } else {
                $this->filesystem->delete($file);
            }
        }
        return true;
    }

    /**
     * Handle uploaded files.
     *
     * @param FileBag $uploadedFiles
     * @return array
     */
    protected function handleUploadedFiles(FileBag $uploadedFiles): array
    {
        $files = [];
        foreach ($uploadedFiles as $name => $uploadedFile) {
            if (!$uploadedFile) {
                continue;
            }
            if (is_array($uploadedFile)) {
                $files[$name] = $this->handleUploadedFiles(new FileBag($uploadedFile));
            } else {
                $files[$name] = $this->handleUploadedFile($uploadedFile);
            }
        }
        return $files;
    }

    protected function handleUploadedFile(UploadedFile $uploadedFile): File
    {
        $name = $this->namer->generate($uploadedFile);
        $file = new File($name, $uploadedFile);

        try {
            // validate the file
            $this->validator->validate($uploadedFile);
            $this->filesystem
                ->upload($file, $this->overwrite);

            $file->setUploaded(true);
        } catch (Exception $exception) {
            $file->setUploaded(false);
            $file->setException($exception);
        }

        return $this->processor->process($file);
    }

    /**
     * @param SymfonyRequest|null $request
     * @return FileBag
     */
    protected function createUploadedFiles(?SymfonyRequest $request = null): FileBag
    {
        if ($request instanceof SymfonyRequest) {
            $files = $request->files;
        } else {
            $files = new FileBag($_FILES);
        }
        return $files;
    }
}

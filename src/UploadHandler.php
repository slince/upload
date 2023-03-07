<?php

namespace Slince\Upload;

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
    protected $namer;

    /**
     * @var FilesystemInterface
     */
    protected $filesystem;

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var ProcessorInterface
     */
    protected $processor;

    /**
     * @var boolean
     */
    protected $overwrite;

    /**
     * @var FileBag
     */
    protected $uploadedFiles;

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
     * Gets all uploaded files.
     *
     * [
     *     'file1' => UploadedFile,
     *     'file2' => [
     *         UploadedFile,
     *         UploadedFile
     *     ],
     * ]
     * @return UploadedFile[]
     */
    public function getUploadedFiles(): array
    {
        return $this->uploadedFiles->all();
    }

    /**
     * Process request
     * @param SymfonyRequest|null $request
     *
     * @return File[]
     */
    public function handle(?SymfonyRequest $request = null): array
    {
        return $this->processUploadedFiles($this->createUploadedFiles($request));
    }

    /**
     * Clear files
     *
     * @param File[] $files
     * @return bool
     */
    public function clear(array $files): bool
    {
        foreach ($files as $file) {
            if (is_array($file)) {
                $this->clear($file);
            } else {
                $this->filesystem->delete($file);
            }
        }

        return true;
    }

    /**
     * @param FileBag|array $uploadedFiles
     * @return array
     */
    protected function processUploadedFiles($uploadedFiles): array
    {
        $files = [];
        foreach ($uploadedFiles as $name => $uploadedFile) {
            if (!$uploadedFile) {
                continue;
            } elseif (is_array($uploadedFile)) {
                $files[$name] = $this->processUploadedFiles($uploadedFile);
            } else {
                $files[$name] = $this->processUploadedFile($uploadedFile);
            }
        }
        return $files;
    }

    protected function processUploadedFile(UploadedFile $uploadedFile): File
    {
        $name = $this->namer->generate($uploadedFile);
        try {
            // validate the file
            $this->validator->validate($uploadedFile);
            $data = $this->filesystem
                ->upload($name, $uploadedFile, $this->overwrite);

            $file = new File($uploadedFile, $name, true, $data);
        } catch (\Exception $exception) {
            $file = new File($uploadedFile, $name, false, null, $exception);
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
        return $this->uploadedFiles = $files;
    }
}

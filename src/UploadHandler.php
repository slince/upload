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

use Slince\Upload\Filesystem\FilesystemInterface;
use Slince\Upload\Naming\NamerInterface;
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
        $overwrite = false
    ) {
        $this->filesystem = $filesystem;
        $this->namer = $namer;
        $this->overwrite = $overwrite;
        $this->validator = new Validator();
    }

    /**
     * Gets the validator
     *
     * @return Validator
     */
    public function getValidator()
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
    public function getUploadedFiles()
    {
        return $this->uploadedFiles->all();
    }

    /**
     * Process request
     * @param SymfonyRequest|null $request
     *
     * @return File[]
     */
    public function handle($request = null)
    {
        return $this->processUploadedFiles($this->createUploadedFiles($request));
    }

    protected function processUploadedFiles($uploadedFiles)
    {
        $files = [];
        foreach ($uploadedFiles as $name => $uploadedFileItem) {
            if (is_array($uploadedFileItem)) {
                $files[$name] = $this->processUploadedFiles($uploadedFileItem);
            } else {
                $files[$name] = $this->processUploadedFile($uploadedFileItem);
            }
        }
        return $files;
    }

    protected function processUploadedFile(UploadedFile $uploadedFile)
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
        return $file;
    }

    /**
     * @param SymfonyRequest|null $request
     * @return FileBag
     */
    protected function createUploadedFiles($request = null)
    {
        if ($request instanceof SymfonyRequest) {
            $files = $request->files;
        } else {
            $files = new FileBag($_FILES);
        }
        return $this->uploadedFiles = $files;
    }
}
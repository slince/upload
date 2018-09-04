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
     * Process request
     * @param SymfonyRequest|null $request
     *
     * @return UploadedFile[]
     */
    public function handle($request = null)
    {
        $files = [];
        foreach ($this->createFiles($request) as $uploadedFile) {
            $files[] = $this->processFile($uploadedFile);
        }
        return $files;
    }

    protected function processFile(UploadedFile $file)
    {
        try {
            // validate the file
            $this->validator->validate($file);
            return $this->filesystem
                ->upload($this->namer->generate($file), $file, $this->overwrite);
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    /**
     * @param SymfonyRequest|null $request
     * @return FileBag
     */
    protected function createFiles($request = null)
    {
        if ($request instanceof SymfonyRequest) {
            $files = $request->files;
        } else {
            $files = new FileBag($_FILES);
        }
        return $files;
    }
}
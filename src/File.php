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

use Symfony\Component\HttpFoundation\File\UploadedFile;

final class File
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    /**
     * Is uploaded
     *
     * @var boolean
     */
    protected $uploaded;

    /**
     * @var \Exception
     */
    protected $exception;

    /**
     * Storage system returned.
     *
     * @var \Symfony\Component\HttpFoundation\File\File|mixed
     */
    protected $details;

    public function __construct(
        UploadedFile $uploadedFile, $name, $uploaded, $details = null, $exception = null)
    {
        $this->uploadedFile = $uploadedFile;
        $this->name = $name;
        $this->uploaded = $uploaded;
        $this->details = $details;
        $this->exception = $exception;
    }

    /**
     * Gets the key generated by `Namer`
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Access file details saved in client by this object.
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->uploadedFile;
    }

    /**
     * Checks whether the file is uploaded successfully.
     *
     * @return bool
     */
    public function isUploaded()
    {
        return $this->uploaded;
    }

    /**
     * The exception if the file is uploaded error.
     *
     * @return \Exception
     */
    public function getException()
    {
        return $this->exception;
    }

    /**
     * File details provided by storage layer.
     *
     * @return \Symfony\Component\HttpFoundation\File\File|mixed
     */
    public function getDetails()
    {
        return $this->details;
    }
}
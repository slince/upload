<?php

namespace Slince\Upload\Exception;

use Slince\Upload\Constraint\ConstraintInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ConstraintException extends UploadException
{
    /**
     * @var ConstraintInterface
     */
    protected $constraint;

    /**
     * @var UploadedFile
     */
    protected $uploadedFile;

    public function __construct(ConstraintInterface $constraint, UploadedFile $uploadedFile)
    {
        $this->constraint = $constraint;
        $this->uploadedFile = $uploadedFile;
        parent::__construct($constraint->getErrorMessage($uploadedFile));
    }

    /**
     * Gets the constraint
     *
     * @return ConstraintInterface
     */
    public function getConstraint(): ConstraintInterface
    {
        return $this->constraint;
    }

    /**
     * Gets the uploaded file
     *
     * @return UploadedFile
     */
    public function getUploadedFile(): UploadedFile
    {
        return $this->file;
    }
}

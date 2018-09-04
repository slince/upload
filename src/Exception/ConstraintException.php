<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
    public function getConstraint()
    {
        return $this->constraint;
    }

    /**
     * Gets the uploaded file
     *
     * @return UploadedFile
     */
    public function getUploadedFile()
    {
        return $this->file;
    }
}
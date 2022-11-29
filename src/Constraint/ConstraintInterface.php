<?php

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ConstraintInterface
{

    /**
     * Validate the file
     *
     * @param UploadedFile $file
     * @return boolean
     */
    public function validate(UploadedFile $file): bool;

    /**
     * Gets error message.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function getErrorMessage(UploadedFile $file): string;

    /**
     * Set error message.
     *
     * @param string $messageTemplate
     * @return void
     */
    public function setErrorMessage(string $messageTemplate): void;
}

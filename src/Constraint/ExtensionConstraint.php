<?php

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtensionConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected $errorMessageTemplate = 'File extension {extension} is invalid';

    /**
     * allowed extensions
     * @var array
     */
    protected $allowedExtensions = [];

    public function __construct(array $allowedExtensions)
    {
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(UploadedFile $file): bool
    {
        return in_array($file->getClientOriginalExtension(), $this->allowedExtensions);
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(UploadedFile $file): string
    {
        return str_replace('{extension}', $file->getClientOriginalExtension(), $this->errorMessageTemplate);
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessage(string $messageTemplate): void
    {
        $this->errorMessageTemplate = $messageTemplate;
    }
}

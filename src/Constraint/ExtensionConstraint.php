<?php

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtensionConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected string $errorMessageTemplate = 'File extension {extension} is invalid';

    /**
     * allowed extensions
     * @var array
     */
    protected array $allowedExtensions = [];

    public function __construct(array $allowedExtensions)
    {
        $this->allowedExtensions = array_map('strtolower', $allowedExtensions);
    }

    /**
     * {@inheritdoc}
     */
    public function validate(UploadedFile $file): bool
    {
        return in_array(strtolower($file->getClientOriginalExtension()), $this->allowedExtensions, true);
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

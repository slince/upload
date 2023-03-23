<?php

namespace Slince\Upload\Constraint;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class MimeTypeConstraint implements ConstraintInterface
{
    /**
     * @var string
     */
    protected string $errorMessageTemplate = 'File type {type} is invalid';

    /**
     * @var array
     */
    protected array $allowedMimeTypes;

    public function __construct(array $allowedMimeTypes)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * {@inheritdoc]
     */
    public function validate(UploadedFile $file): bool
    {
        foreach ($this->allowedMimeTypes as $mimeType) {
            if ($mimeType === $file->getClientMimeType()
                || (str_contains($mimeType, '/*')
                    && explode('/', $mimeType)[0] === explode('/', $file->getMimeType())[0])
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(UploadedFile $file): string
    {
        return str_replace('{type}', $file->getClientMimeType(), $this->errorMessageTemplate);
    }

    /**
     * {@inheritdoc}
     */
    public function setErrorMessage(string $messageTemplate): void
    {
        $this->errorMessageTemplate = $messageTemplate;
    }
}

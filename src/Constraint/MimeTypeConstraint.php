<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Constraint;

use Slince\Upload\Exception\ConstraintException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class MimeTypeConstraint implements ConstraintInterface
{
    /**
     * @var array
     */
    protected $allowedMimeTypes;

    public function __construct(array $allowedMimeTypes)
    {
        $this->allowedMimeTypes = $allowedMimeTypes;
    }

    /**
     * {@inheritdoc]
     */
    public function validate(UploadedFile $file)
    {
        foreach ($this->allowedMimeTypes as $mimeType) {
            if ($mimeType === $file->getClientMimeType()
                || (strpos($mimeType, '/*') !== false
                    && explode('/', $mimeType)[0] == explode('/', $file->getMimeType())[0])
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorMessage(UploadedFile $file)
    {
        return sprintf('File type "%s" is invalid', $file->getClientMimeType());
    }
}
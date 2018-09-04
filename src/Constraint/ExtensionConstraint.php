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

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ExtensionConstraint implements ConstraintInterface
{
    /**
     * allowed extensions
     * @var array
     */
    protected $allowedExtensions = [];

    public function __construct(array $allowedExtensions)
    {
        $this->allowedExtensions[] = $allowedExtensions;
    }

    /**
     * {@inheritdoc}
     */
    public function validate(UploadedFile $file)
    {
        if (!in_array($file->getExtension(), $this->allowedExtensions)) {
            return false;
        }
        return true;
    }
}
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

interface ConstraintInterface
{

    /**
     * Validate the file
     *
     * @param UploadedFile $file
     * @return boolean
     */
    public function validate(UploadedFile $file);

    /**
     * Gets error message.
     *
     * @param UploadedFile $file
     * @return string
     */
    public function getErrorMessage(UploadedFile $file);
}
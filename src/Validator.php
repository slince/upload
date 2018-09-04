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

use Slince\Upload\Constraint\ConstraintInterface;
use Slince\Upload\Exception\ConstraintException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class Validator
{
    /**
     * Array of constraints
     *
     * @var ConstraintInterface[]
     */
    protected $constraints = [];

    /**
     * Add a constraint
     * @param ConstraintInterface $constraint
     */
    public function addConstraint(ConstraintInterface $constraint)
    {
        $this->constraints[] = $constraint;
    }

    /**
     * Validate the file
     *
     * @param UploadedFile $file
     * @return true
     * @throws ConstraintException
     */
    public function validate(UploadedFile $file)
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->validate($file)) {
                throw new ConstraintException($constraint, $file);
            }
        }
        return true;
    }
}
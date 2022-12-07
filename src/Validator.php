<?php

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

    public function __construct(array $constraints = [])
    {
        $this->constraints = $constraints;
    }

    /**
     * Add a constraint
     * @param ConstraintInterface $constraint
     */
    public function addConstraint(ConstraintInterface $constraint): void
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
    public function validate(UploadedFile $file): bool
    {
        foreach ($this->constraints as $constraint) {
            if (!$constraint->validate($file)) {
                throw new ConstraintException($constraint, $file);
            }
        }
        return true;
    }
}

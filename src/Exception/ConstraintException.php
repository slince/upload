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
use Throwable;

class ConstraintException extends UploadException
{
    /**
     * @var ConstraintInterface
     */
    protected $constraint;

    public function __construct(ConstraintInterface $constraint)
    {
        $this->constraint = $constraint;
        parent::__construct($message, $code, $previous);
    }

    /**
     * @return ConstraintInterface
     */
    public function getConstraint()
    {
        return $this->constraint;
    }
}
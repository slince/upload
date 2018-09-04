<?php

/*
 * This file is part of the slince/upload package.
 *
 * (c) Slince <taosikai@yeah.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slince\Upload\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenericNamer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(UploadedFile $file)
    {
        return $file->getClientOriginalName();
    }
}
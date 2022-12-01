<?php

namespace Slince\Upload\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class GenericNamer implements NamerInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(UploadedFile $file): string
    {
        return $file->getClientOriginalName();
    }
}

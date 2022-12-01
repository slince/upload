<?php

namespace Slince\Upload\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface NamerInterface
{
    /**
     * Generate the key
     * @param UploadedFile $file
     * @return string
     */
    public function generate(UploadedFile $file): string;
}

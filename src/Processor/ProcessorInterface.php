<?php

namespace Slince\Upload\Processor;

use Slince\Upload\File;

interface ProcessorInterface
{
    /**
     * Process the given file instance.
     *
     * @param File $file
     * @return File
     */
    public function process(File $file): File;
}

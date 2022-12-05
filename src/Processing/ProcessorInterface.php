<?php

namespace Slince\Upload\Processing;

use Slince\Upload\File;

interface ProcessorInterface
{
    public function process(File $file): File;
}

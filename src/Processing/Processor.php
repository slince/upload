<?php

namespace Slince\Upload\Processing;

use Slince\Upload\File;

class Processor implements ProcessorInterface
{
    protected $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $file): File
    {
        call_user_func($this->closure, $file);

        return $file;
    }
}

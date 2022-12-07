<?php

namespace Slince\Upload\Processor;

use Slince\Upload\File;

class ClosureProcessor implements ProcessorInterface
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
        return call_user_func($this->closure, $file);
    }
}

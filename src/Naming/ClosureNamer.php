<?php

namespace Slince\Upload\Naming;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ClosureNamer implements NamerInterface
{
    protected $closure;

    public function __construct(\Closure $closure)
    {
        $this->closure = $closure;
    }

    /**
     * {@inheritdoc}
     */
    public function generate(UploadedFile $file): string
    {
        return call_user_func($this->closure, $file);
    }
}

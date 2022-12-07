<?php

namespace Slince\Upload\Processor;

use Slince\Upload\File;

final class ChainProcessor implements ProcessorInterface
{
    /**
     * @var ProcessorInterface[]
     */
    protected $processors = [];

    public function __construct(array $processors = [])
    {
        $this->processors = $processors;
    }

    /**
     * {@inheritdoc}
     */
    public function process(File $file): File
    {
        foreach ($this->processors as $processor) {
            $file = $processor->process($file);
        }
        return $file;
    }
}

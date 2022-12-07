<?php

namespace Slince\Upload;

use Slince\Upload\Processing\ProcessorInterface;

final class FileProcessor
{
    /**
     * @var ProcessorInterface[]
     */
    protected $processList = [];

    public function addProcess(ProcessorInterface $process): void
    {
        $this->processList[] = $process;
    }

    public function process(File $file): bool
    {
        foreach ($this->processList as $process) {
            $process->process($file);
        }
        return true;
    }
}

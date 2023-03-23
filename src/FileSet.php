<?php

namespace Slince\Upload;

use Symfony\Component\HttpFoundation\FileBag;
use Traversable;

final class FileSet implements \IteratorAggregate, \Countable
{
    /**
     * @var File[]
     */
    protected array $files;

    protected ?FileBag $uploadedFiles;

    public function __construct(array $files, ?FileBag $uploadedFiles = null)
    {
        $this->files = $files;
        $this->uploadedFiles = $uploadedFiles;
    }

    /**
     * Gets all raw uploaded files.
     *
     * @return ?FileBag
     */
    public function getUploadedFiles(): ?FileBag
    {
        return $this->uploadedFiles;
    }

    /**
     * Return all files.
     *
     * @return File[]
     */
    public function all(): array
    {
        return $this->files;
    }

    /**
     * Checks whether the file with given field name is exists.
     *
     * @param string $name
     * @return bool
     */
    public function has(string $name): bool
    {
        return isset($this->files[$name]);
    }

    /**
     * Returns the file with given field name
     * @param string $name
     * @return File|null
     */
    public function get(string $name): ?File
    {
        return $this->files[$name] ?? null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new \ArrayIterator($this->files);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->files);
    }
}
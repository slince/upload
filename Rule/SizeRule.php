<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class SizeRule extends AbstractRule
{
    /**
     * Maximum file size
     * @var int
     */
    protected $maxSize;

    /**
     * Minimum file size
     * @var int
     */
    protected $minSize;

    /**
     * Limit file size (use "B", "K", M", or "G")
     * @param int|string $minSize
     * @param int|string $maxSize
     */
    public function __construct($minSize, $maxSize)
    {
        if ($minSize) {
            $this->minSize = FileInfo::humanReadableToBytes($minSize);
        }
        if ($maxSize) {
            $this->maxSize = FileInfo::humanReadableToBytes($maxSize);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        $size = $file->getSize();
        $result = (!$this->maxSize || $size <= $this->maxSize)
            && (!$this->minSize || $size >= $this->minSize);
        if (!$result) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_SIZE;
            $this->errorMsg = 'File size is not valid';
        }
        return $result;
    }
}

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

    protected $maxSize;

    protected $minSize;

    public function __construct($start, $end = null)
    {
        if (is_null($end)) {
            $this->maxSize = $start;
        } else {
            $this->minSize = $start;
            $this->maxSize = $end;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        $size = $file->getSize();
        $res = $size <= $this->maxSize;
        if (!is_null($this->minSize)) {
            $res = $size >= $this->minSize;
        }
        if (!$res) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_SIZE;
            $this->errorMsg = 'File size is not valid';
        }
        return $res;
    }
}

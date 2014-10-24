<?php
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Applicaion\EventStore;
use Slince\Upload\ErrorStore;

class SizeRule extends AbstractRule
{

    private $_maxSize;

    private $_minSize;

    function __construct($start, $end = null)
    {
        if (is_null($end)) {
            $this->_maxSize = $start;
        } else {
            $this->_minSize = $start;
            $this->_maxSize = $end;
        }
    }

    function validate(FileInfo $file)
    {
        $size = $file->getSize();
        $res = $size <= $this->_maxSize;
        if (! is_null($this->_minSize)) {
            $res = $size >= $this->_minSize;
        }
        if (! $res) {
            $this->_errorCode = ErrorStore::ERROR_CUSTOM_SIZE;
            $this->_errorMsg = 'File size is not valid';
        }
        return $res;
    }
} 
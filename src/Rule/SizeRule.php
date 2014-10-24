<?php
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;

class SizeRule extends AbstractRule
{
    protected $_errorCode = 100;
    
    protected $_defaultErrorMsg = 'File size is not valid';
    
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
            $this->_errorMsg = $this->_defaultErrorMsg;
        }
        return $res;
    }
} 
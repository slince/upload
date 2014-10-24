<?php
namespace Slince\Upload;

class FileInfo
{

    public $hasError = false;

    private $_tmpName = '';

    private $_originName = '';

    private $_error = '';

    private $_type = '';

    private $_size = '';

    private $_errorCode = '';

    private $_errorMsg = '';

    private $_path = '';

    function __construct($_F)
    {}

    static function createFromArray(array $info)
    {
        return new self();
    }

    function getSize()
    {
        return $this->_size;
    }

    function getMimeType()
    {}

    function getOriginName()
    {
        return $this->_originName;
    }

    function getError()
    {
        return $this->_error;
    }

    function setErrorCode($code)
    {
        $this->_errorCode = $code;
    }

    function getErrorCode()
    {
        return $this->_errorCode;
    }

    function setErrorMsg($msg)
    {
        $this->_errorMsg = $msg;
    }

    function getErrorMsg()
    {
        return $this->_errorMsg;
    }

    function setPath($path)
    {
        $this->_path = $path;
    }
}
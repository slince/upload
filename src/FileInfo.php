<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

class FileInfo
{

    public $hasError = true;

    private $_tmpName = '';

    private $_originName = '';

    private $_error = '';

    private $_type = '';

    private $_size = '';

    private $_errorCode = '';

    private $_errorMsg = '';

    private $_path = '';

    function __construct(array $file)
    {
        $this->_tmpName = $file['tmp_name'];
        $this->_originName = $file['name'];
        $this->_error = $file['error'];
        $this->_size = $file['size'];
        $this->_type = $file['type'];
    }

    static function createFromArray(array $info)
    {
        return new self($info);
    }

    function getSize()
    {
        return $this->_size;
    }

    function getMimeType()
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE, $this->_tmpName);
        $mime = $finfo->file();
        return $mime;
    }

    function getExtension()
    {
        return pathinfo($this->_originName, PATHINFO_EXTENSION);
    }
    
    function getTmpName()
    {
        return $this->_tmpName;
    }
    
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
    function getPath()
    {
        return $this->_path;
    }
}
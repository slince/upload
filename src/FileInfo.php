<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

class FileInfo
{

    /**
     * 是否上传失败
     *
     * @var boolean
     */
    public $hasError = true;

    /**
     * 临时文件名
     *
     * @var string
     */
    private $_tmpName;

    /**
     * 源文件名，保存在客户端的名称
     *
     * @var string
     */
    private $_originName;

    /**
     * 上传过程中出现的错误
     *
     * @var int
     */
    private $_error;

    /**
     * 文件类型，没有检测，不使用
     *
     * @var string
     */
    private $_type;

    /**
     * 文件大小
     *
     * @var int
     */
    private $_size;

    /**
     * 最终的错误码
     *
     * @var int
     */
    private $_errorCode;

    /**
     * 出现的错误信息
     *
     * @var string
     */
    private $_errorMsg;

    /**
     * 成功上传之后保存的文件路径
     *
     * @var string
     */
    private $_path = '';

    function __construct(array $file)
    {
        $this->_tmpName = $file['tmp_name'];
        $this->_originName = $file['name'];
        $this->_error = $file['error'];
        $this->_size = $file['size'];
        $this->_type = $file['type'];
    }

    /**
     * 根据信息数组获取实例
     *
     * @param array $info
     * @return \Slince\Upload\FileInfo
     */
    static function createFromArray(array $info)
    {
        return new self($info);
    }

    /**
     * 获取文件大小
     *
     * @return int
     */
    function getSize()
    {
        return $this->_size;
    }

    /**
     * 获取文件类型
     *
     * @return string
     */
    function getMimeType()
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($this->_tmpName);
        return $mime;
    }

    /**
     * 获取扩展名
     *
     * @return string
     */
    function getExtension()
    {
        return pathinfo($this->_originName, PATHINFO_EXTENSION);
    }

    /**
     * 获取临时文件名
     *
     * @return string
     */
    function getTmpName()
    {
        return $this->_tmpName;
    }

    /**
     * 获取源文件名称
     *
     * @return string
     */
    function getOriginName()
    {
        return $this->_originName;
    }

    /**
     * 获取上传过程出现的错误
     *
     * @return int
     */
    function getError()
    {
        return $this->_error;
    }

    /**
     * 设置最终错误代码
     *
     * @param int $code
     */
    function setErrorCode($code)
    {
        $this->_errorCode = $code;
    }

    /**
     * 获取最终错误错误代码
     *
     * @return int
     */
    function getErrorCode()
    {
        return $this->_errorCode;
    }

    /**
     * 设置错误信息
     *
     * @param string $msg 
     */
    function setErrorMsg($msg)
    {
        $this->_errorMsg = $msg;
    }

    /**
     * 设置错误信息
     *
     * @return string
     */
    function getErrorMsg()
    {
        return $this->_errorMsg;
    }

    /**
     * 设置最终文件路径
     *
     * @param string $path
     */
    function setPath($path)
    {
        $this->_path = $path;
    }

    /**
     * 获取最终文件路径
     *
     * @return string
     */
    function getPath()
    {
        return $this->_path;
    }
}
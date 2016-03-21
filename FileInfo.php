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
    private $tmpName;

    /**
     * 源文件名，保存在客户端的名称
     *
     * @var string
     */
    private $originName;

    /**
     * 上传过程中出现的错误
     *
     * @var int
     */
    private $error;

    /**
     * 文件类型，没有检测，不使用
     *
     * @var string
     */
    private $type;

    /**
     * 文件大小
     *
     * @var int
     */
    private $size;
    
    /**
     * 文件mime类型
     * 
     * @var string
     */
    private $mime;

    /**
     * 最终的错误码
     *
     * @var int
     */
    private $errorCode;

    /**
     * 出现的错误信息
     *
     * @var string
     */
    private $errorMsg;

    /**
     * 成功上传之后保存的文件路径
     *
     * @var string
     */
    private $path = '';

    function __construct(array $file)
    {
        $this->tmpName = $file['tmp_name'];
        $this->originName = $file['name'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->type = $file['type'];
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
        return $this->size;
    }

    /**
     * 获取文件类型
     *
     * @return string
     */
    function getMimeType()
    {
        if (is_null($this->mime)) {
            if (class_exists('finfo')) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $this->mime = $finfo->file($this->tmpName);
            } else {
                $mime = MimeTypeStore::getMimeType($this->getExtension());
                $this->mime = is_array($mime) ? reset($mime) : $mime;
            }
        }
        return $this->mime;
    }

    /**
     * 获取扩展名
     *
     * @return string
     */
    function getExtension()
    {
        return pathinfo($this->originName, PATHINFO_EXTENSION);
    }

    /**
     * 获取临时文件名
     *
     * @return string
     */
    function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * 获取源文件名称
     *
     * @return string
     */
    function getOriginName()
    {
        return $this->originName;
    }

    /**
     * 获取上传过程出现的错误
     *
     * @return int
     */
    function getError()
    {
        return $this->error;
    }

    /**
     * 设置最终错误代码
     *
     * @param int $code
     */
    function setErrorCode($code)
    {
        $this->errorCode = $code;
    }

    /**
     * 获取最终错误错误代码
     *
     * @return int
     */
    function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 设置错误信息
     *
     * @param string $msg 
     */
    function setErrorMsg($msg)
    {
        $this->errorMsg = $msg;
    }

    /**
     * 设置错误信息
     *
     * @return string
     */
    function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * 设置最终文件路径
     *
     * @param string $path
     */
    function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * 获取最终文件路径
     *
     * @return string
     */
    function getPath()
    {
        return $this->path;
    }
}
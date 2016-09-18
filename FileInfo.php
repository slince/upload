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
     * @var boolean
     */
    public $hasError = true;

    /**
     * 临时文件名
     * @var string
     */
    protected $tmpName;

    /**
     * 源文件名，保存在客户端的名称
     *
     * @var string
     */
    protected $originName;

    /**
     * 上传过程中出现的错误
     * @var int
     */
    protected $error;

    /**
     * 文件类型，没有检测，不使用
     * @var string
     */
    protected $type;

    /**
     * 文件大小
     * @var int
     */
    protected $size;

    /**
     * 文件mime类型
     * @var string
     */
    protected $mime;

    /**
     * 最终的错误码
     * @var int
     */
    protected $errorCode;

    /**
     * 出现的错误信息
     * @var string
     */
    protected $errorMsg;

    /**
     * 成功上传之后保存的文件路径
     *
     * @var string
     */
    protected $path = '';

    public function __construct(array $file)
    {
        $this->tmpName = $file['tmp_name'];
        $this->originName = $file['name'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->type = $file['type'];
    }

    /**
     * 根据信息数组获取实例
     * @param array $info
     * @return FileInfo
     */
    public static function createFromArray(array $info)
    {
        return new static($info);
    }

    /**
     * 获取文件大小
     *
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * 获取文件类型
     * @return string
     */
    public function getMimeType()
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
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->originName, PATHINFO_EXTENSION);
    }

    /**
     * 获取临时文件名
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * 获取源文件名称
     * @return string
     */
    public function getOriginName()
    {
        return $this->originName;
    }

    /**
     * 获取上传过程出现的错误
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * 设置最终错误代码
     * @param int $code
     */
    public function setErrorCode($code)
    {
        $this->errorCode = $code;
    }

    /**
     * 获取最终错误错误代码
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * 设置错误信息
     * @param string $msg
     */
    public function setErrorMsg($msg)
    {
        $this->errorMsg = $msg;
    }

    /**
     * 设置错误信息
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * 设置最终文件路径
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * 获取最终文件路径
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}

<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload;

use Slince\Upload\Exception\UploadException;

class FileInfo
{
    /**
     * has error
     * @var boolean
     */
    public $hasError = true;

    /**
     * tmp filename, $_FILES['upfile']['tmp_name']
     * @var string
     */
    protected $tmpName;

    /**
     * Original file name(the name saved on the client); $_FILES['upfile']['name']
     * @var string
     */
    protected $originName;

    /**
     * errors;$_FILES['error']
     * @var int
     */
    protected $error;

    /**
     * file size(bytes);$_FILES['upfile']['size']
     * @var int
     */
    protected $size;

    /**
     * file type;$_FILES['upfile']['type']
     * @var string
     */
    protected $type;

    /**
     * file mime type
     * @var string
     */
    protected $mimeType;

    /**
     * error code
     * @var int
     */
    protected $errorCode;

    /**
     * error message
     * @var string
     */
    protected $errorMsg;

    /**
     * The file path after upload
     * @var string
     */
    protected $path;

    public function __construct(array $file)
    {
        if (!isset($file['tmp_name'])
            || !isset($file['name'])
            || !isset($file['error'])
            || !isset($file['size'])
            || !isset($file['type'])
        ) {
            throw new UploadException("Invalid file array");
        }
        $this->tmpName = $file['tmp_name'];
        $this->originName = $file['name'];
        $this->error = $file['error'];
        $this->size = $file['size'];
        $this->type = $file['type'];
    }

    /**
     * Create instance from array
     * @param array $info
     * @return FileInfo
     */
    public static function fromArray(array $info)
    {
        return static::createFromArray($info);
    }

    /**
     * Create instance from array
     * @param array $info
     * @return FileInfo
     * @deprecated
     */
    public static function createFromArray(array $info)
    {
        return new static($info);
    }

    /**
     * gets file size
     * @return int
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * gets file mime type
     * @return string
     */
    public function getMimeType()
    {
        if (is_null($this->mimeType)) {
            $this->mimeType = $this->detectMimeType();
        }
        return $this->mimeType;
    }

    /**
     * detect file mime type
     * @return string
     */
    protected function detectMimeType()
    {
        $mimeType = null;
        if (class_exists('finfo')) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->file($this->tmpName);
        }
        if (!$mimeType)  {
            $mimeType = MimeTypeStore::getMimeType($this->getExtension());
            $mimeType = is_array($mimeType) ? reset($mimeType) : $mimeType;
        }
        return $mimeType;
    }

    /**
     * gets file extension
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->originName, PATHINFO_EXTENSION);
    }

    /**
     * gets file tmp name
     * @return string
     */
    public function getTmpName()
    {
        return $this->tmpName;
    }

    /**
     * gets original file name
     * @return string
     */
    public function getOriginName()
    {
        return $this->originName;
    }

    /**
     * gets file type
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * gets error($_FILES['upfile']['error'])
     * @return int
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * set error code
     * @param int $code
     */
    public function setErrorCode($code)
    {
        $this->errorCode = $code;
    }

    /**
     * gets final error code
     * @return int
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * set error message
     * @param string $msg
     */
    public function setErrorMsg($msg)
    {
        $this->errorMsg = $msg;
    }

    /**
     * get error message
     * @return string
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * set file path
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * get file path
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * whether there is an error
     * @return bool
     */
    public function hasError()
    {
        return $this->hasError;
    }

    /**
     * set has error
     * @param $result
     */
    public function setHasError($result)
    {
        $this->hasError = $result;
    }
}

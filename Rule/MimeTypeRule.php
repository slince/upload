<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class MimeTypeRule extends AbstractRule
{
    /**
     * 允许的文件类型
     * @var array
     */
    protected $allowTypes = [];

    /**
     * 常用的图片类型
     * @var array
     */
    public static $universalImages = [
        'image/gif',
        'image/jpeg',
        'image/png'
    ];

    /**
     * 常用的文档类型
     * @var array
     */
    public static $universalDocuments = [
        'text/plain',
        'application/msword',
        'application/vnd.ms-excel',
        'application/pdf'
    ];

    /**
     * 常用的文件类型
     * @var array
     */
    public static $universalFiles = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'text/plain',
        'application/msword',
        'application/vnd.ms-excel',
        'application/pdf'
    ];

    public function __construct($allowType)
    {
        if (is_array($allowType)) {
            $this->allowTypes = $allowType;
        } else {
            $this->allowTypes[] = $allowType;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        if (!in_array($file->getMimeType(), $this->allowTypes)) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_MIME_TYPE;
            $this->errorMsg = 'File type is not valid';
            return false;
        }
        return true;
    }
}

<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;

class ExtensionRule extends AbstractRule
{
    /**
     * 允许的扩展名
     * @var array
     */
    protected $allowExtensions = [];

    /**
     * 常用的图片扩展名
     * @var array
     */
    protected static $universalImages = [
        'gif',
        'jpg',
        'jpeg'
    ];

    /**
     * 常用的文档扩展名
     * @var array
     */
    protected static $universalDocuments = [
        'txt',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

    /**
     * 常用的文件扩展名
     * @var array
     */
    protected static $universalFiles = [
        'gif',
        'jpg',
        'jpeg',
        'txt',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

    public function __construct($allowExtensions)
    {
        if (is_array($allowExtensions)) {
            $this->allowExtensions = $allowExtensions;
        } else {
            $this->allowExtensions[] = $allowExtensions;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function validate(FileInfo $file)
    {
        if (!in_array($file->getExtension(), $this->allowExtensions)) {
            $this->errorCode = ErrorStore::ERROR_CUSTOM_EXT;
            $this->errorMsg = 'File extension is not valid';
            return false;
        }
        return true;
    }
}

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

    protected $allowExtensions = [];

    protected static $universalImages = [
        'gif',
        'jpg',
        'jpeg'
    ];

    protected static $universalDocuments = [
        'txt',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

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

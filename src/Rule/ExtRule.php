<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;
use Slince\Applicaion\EventStore;

class ExtRule extends AbstractRule
{

    private $_allowExts = [];

    static $universalImages = [
        'gif',
        'jpg',
        'jpeg'
    ];

    static $universalDocuments = [
        'txt',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx'
    ];

    static $universalFiles = [
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

    function __construct($allowExt)
    {
        if (is_array($allowExt)) {
            $this->_allowExts = $allowExt;
        } else {
            $this->_allowExts[] = $allowExt;
        }
    }

    function validate(FileInfo $file)
    {
        if (in_array($file->getExtension(), $this->_allowExts)) {
            $this->_errorCode = ErrorStore::ERROR_CUSTOM_EXT;
            $this->_errorMsg = 'File extension is not valid';
            return false;
        }
        return true;
    }
}
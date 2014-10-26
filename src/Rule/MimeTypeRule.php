<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;
use Slince\Upload\ErrorStore;
use Slince\Applicaion\EventStore;

class MimeTypeRule extends AbstractRule
{

    private $_allowTypes = [];

    static $universalImages = [
        'image/gif',
        'image/jpeg',
        'image/png'
    ];

    static $universalDocuments = [
        'text/plain',
        'application/msword',
        'application/vnd.ms-excel',
        'application/pdf'
    ];

    static $universalFiles = [
        'image/gif',
        'image/jpeg',
        'image/png',
        'text/plain',
        'application/msword',
        'application/vnd.ms-excel',
        'application/pdf'
    ];

    function __construct($allowType)
    {
        if (is_array($allowType)) {
            $this->_allowTypes = $allowType;
        } else {
            $this->_allowTypes[] = $allowType;
        }
    }

    function validate(FileInfo $file)
    {
        if (in_array($file->getMimeType(), $this->_allowTypes)) {
            $this->_errorCode = ErrorStore::ERROR_CUSTOM_MIME_TYPE;
            $this->_errorMsg = 'File type is not valid';
            return false;
        }
        return true;
    }
}
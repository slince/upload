<?php
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;

class MimeTypeRule extends AbstractRule
{
    protected $_errorCode = 101;
    
    protected $_defaultErrorMsg= 'File type is not valid';
    
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
            $this->_errorMsg = $this->_defaultErrorMsg;
            return false;
        }
        return true;
    }
}
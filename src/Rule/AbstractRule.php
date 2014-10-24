<?php
namespace Slince\Upload\Rule;

use Slince\Upload\RuleInterface;

class AbstractRule implements RuleInterface
{
    protected $_errorMsg = 'No error';
    
    protected $_errorCode = 0;
    
    protected $_defaultErrorMsg = 'No error';
    
    
    function getErrorMsg()
    {
        return $this->_errorMsg;
    }
    
    function getErrorCode()
    {
        return $this->_errorCode;
    }
    
    function setDefaultErrorMsg($msg)
    {
        $this->_defaultErrorMsg = $msg;
    }
}
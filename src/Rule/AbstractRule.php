<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\RuleInterface;
use Slince\Upload\ErrorStore;

class AbstractRule implements RuleInterface
{

    protected $_errorMsg = 'No error';

    protected $_errorCode = ErrorStore::ERROR_OK;

    function getErrorMsg()
    {
        return $this->_errorMsg;
    }

    function getErrorCode()
    {
        return $this->_errorCode;
    }
}
<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\RuleInterface;
use Slince\Upload\ErrorStore;

abstract class AbstractRule implements RuleInterface
{
    protected $errorMsg = 'No error';

    protected $errorCode = ErrorStore::ERROR_OK;


    /**
     * {@inheritdoc}
     */
    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    /**
     * {@inheritdoc}
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }
}

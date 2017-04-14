<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\ErrorStore;

abstract class AbstractRule implements RuleInterface
{
    /**
     * error code
     * @var int
     */
    protected $errorCode = ErrorStore::ERROR_OK;

    /**
     * error message
     * @var string
     */
    protected $errorMsg = 'No error';

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

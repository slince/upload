<?php
/**
 * slince upload handler library
 * @author Tao <taosikai@yeah.net>
 */
namespace Slince\Upload\Rule;

use Slince\Upload\FileInfo;

interface RuleInterface
{
    /**
     * validate
     * @param FileInfo $file
     * @return boolean
     */
    public function validate(FileInfo $file);

    /**
     * get error code
     * @return string
     */
    public function getErrorMsg();

    /**
     * get error message
     * @return int
     */
    public function getErrorCode();
}

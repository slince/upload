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
     * 验证规则
     * @param FileInfo $file
     * @return boolean
     */
    public function validate(FileInfo $file);

    /**
     * 获取错误信息
     * @return string
     */
    public function getErrorMsg();

    /**
     * 获取错误代码
     * @return int
     */
    public function getErrorCode();
}

<?php
namespace Slince\Upload;

interface RuleInterface
{
    /**
     * 验证规则
     * 
     * @return boolean
     */
    function validate(FileInfo $file);
    
    /**
     * 获取错误信息
     * 
     * @return string
     */
    function getErrorMsg();
    
    function getErrorCode();
    
}
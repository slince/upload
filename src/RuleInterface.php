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
}